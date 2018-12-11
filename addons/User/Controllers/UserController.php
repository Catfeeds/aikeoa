<?php namespace Aike\User\Controllers;

use DB;
use Auth;
use Input;
use Hash;
use Request;
use Validator;
use URL;
use File;

use Totp;
use Pinyin;

use App\License;

use Aike\Hr\Hr;
use Aike\User\UserPosition;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class UserController extends DefaultController
{
    public $permission = ['dialog', 'contact', 'view', 'profile', 'password', 'avatar'];

    public function indexAction()
    {
        $search = search_form([
            'status'   => 1,
            'referer'  => 1,
        ], [
            ['text','user.nickname','姓名'],
            ['text','user.username','账号'],
            ['text','user.id','ID'],
            ['text','user.email','邮箱'],
            ['role','user.role_id','角色'],
            ['birthday','user.birthday','生日'],
            ['department','user.department_id','部门']
        ]);

        $query = $search['query'];

        $model = User::group('user')
        ->withAt('department', ['id', 'title'])
        ->withAt('role', ['id', 'title']);

        if ($query['order'] && $query['srot']) {
            $model->orderBy($query['srot'], $query['order']);
        } else {
            $model->orderBy('id', 'asc');
        }

        $model->where('user.status', $query['status']);

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->paginate($search['limit'])->appends($query);
        
        $tabs = [
            'name'  => 'status',
            'items' => [
                ['id' => 1, 'name' => '启用'],
                ['id' => 0, 'name' => '停用'],
            ]
        ];

        return $this->display([
            'search'    => $search,
            'query'     => $query,
            'rows'      => $rows,
            'tabs'      => $tabs,
            'positions' => $positions,
        ]);
    }

    // app 联系人列表
    public function contactAction()
    {
        $id = Input::get('id');
        $users = User::where('status', 1)->where('group_id', 1)->get(['id','nickname','username', 'email', 'mobile']);

        $json = [];
        foreach ($users as $key => $user) {
            $index = Pinyin::getfirstchar($user['nickname']);
            if (empty($json[$index])) {
                $json[$index][] = ['template' => 0, 'titleText' => $index];
            }
            $json[$index][] = [
                'template' => 1,
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'mobile'   => $user['mobile'],
                'text'     => $user['nickname'],
                'family'   => mb_substr($user['nickname'], 0, 1),
                'image'    => '',
                'label'    => ''
            ];
        }
        return response()->json($json);
    }

    // 查看用户
    public function viewAction()
    {
        $id = Input::get('id', auth()->id());
        $res = User::where('id', $id)->first();

        if (Input::wantsJson()) {
            $res['family'] = mb_substr($res['nickname'], 0, 1);
            return response()->json($res);
        }

        $t = new Totp();
        $secretImg = $t->getURL($res['username'], Request::server('HTTP_HOST'), $res['auth_secret']);

        // 返回json
        if (Request::wantsJson()) {
            return response()->json($res);
        }

        return $this->display([
            'secretKey' => $res['auth_secret'],
            'secretImg' => $secretImg,
            'res'       => $res,
        ]);
    }

    // 账户修改
    public function addAction()
    {
        $gets = Input::get();

        $count = User::group('user')->count('id');
        $license = License::check('user', $count);

        if ($gets['id'] == 0 && $license) {
            return $this->error('无法新建用户授权许可不足。');
        }

        if (Request::method() == 'POST') {
            $gets['group_id']       = 1;
            $gets['status']         = (int)$gets['status'];
            $gets['access']         = (int)$gets['access'];
            $gets['auth_totp']      = (int)$gets['auth_totp'];
            $gets['auth_device']    = (int)$gets['auth_device'];
            $gets['lunar_birthday'] = (int)$gets['lunar_birthday'];
            
            $model = User::findOrNew($gets['id']);
            
            $v = Validator::make($gets, [
                'username'      => 'required|unique:user,username,'.$gets['id'],
                'nickname'      => 'required',
                'department_id' => 'required',
                'role_id'       => 'required',
                'password'      => 'min:6',
                'post'          => 'required',
            ], [
                'required'      => ':attribute不能为空',
                'min'           => ':attribute不能小于6位',
                'unique'        => ':attribute已经存在'
            ], [
                'username'      => '用户名',
                'nickname'      => '姓名',
                'department_id' => '部门',
                'role_id'       => '角色',
                'post'          => '职位',
                'password'      => '密码',
            ]);
            
            if ($v->fails()) {
                return $this->json(implode("<br>", $v->errors()->all()));
            }
            
            $model->username = $gets['username'];
            
            if ($gets['password']) {
                $model->password      = bcrypt($gets['password']);
                $model->password_text = $gets['password'];
            }

            $model->fill($gets)->save();

            // 删除已经关联的人事档案
            Hr::where('user_id', $model->id)->update(['user_id' => 0]);
            
            // 关联新的人事档案
            Hr::where('id', $gets['hr_id'])->update(['user_id' => $model->id]);
            
            return $this->json('恭喜您，操作成功。', "index");
        }

        $row = User::findOrNew($gets['id']);
        $positions = UserPosition::get();

        return $this->display([
            'row'       => $row,
            'positions' => $positions,
        ]);
    }

    /**
     * 更新安全密钥
     *
     */
    public function secretAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $t = new Totp();
            $secretKey = $t->generateSecret();
            $data['auth_secret'] = $secretKey;
            User::where('id', $id)->update($data);
            return $this->json($secretKey);
        }
    }

    public function dialogAction()
    {
        $group_id = Input::get('group_id', 1);
        $search = search_form([
            'advanced' => '',
            'offset'   => 0,
            'sort'     => '',
            'order'    => '',
            'limit'    => 25
        ], [
            ['text','user.nickname','姓名'],
            ['text','user.username','账号'],
            ['text','user.id','编号'],
            ['role','user.role_id','角色'],
            ['department','user.department_id','部门'],
            ['region','user.province_id','地址'],
        ]);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = DB::table('user')
            ->where('status', 1)
            ->where('group_id', $group_id);

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy($query['sort'], $query['order']);
            }

            // 搜索条件
            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }
            $model->selectRaw('id,concat("u",id) as sid,role_id,status,username,nickname,nickname as text,email,mobile');
            $rows = $model->paginate($query['limit']);
            /*
            $json['total'] = $model->count();
            $rows = $model->skip($query['offset'])->take($query['limit'])
            ->get(['id', DB::raw('concat("u",id) as sid'), 'role_id', 'status', 'username', 'nickname', 'email', 'mobile']);
            $json['data'] = $rows;
            */
            return response()->json($rows);
        }
        $get = Input::get();

        return $this->render(array(
            'search' => $search,
            'query'  => $query,
        ));
    }

    /* 修改密码 */
    public function passwordAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $user = User::find(Auth::id());

            $rules = [
                'old_password'              => 'required',
                'new_password'              => 'required|confirmed|different:old_password',
                'new_password_confirmation' => 'required|different:old_password|same:new_password'
            ];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v);
            }

            // 旧密码不正确
            if (Hash::check($gets['old_password'], $user->getAuthPassword()) === false) {
                return $this->back()->withErrors(['old password' => 'old password 不正确。']);
            }

            $user->password = bcrypt($gets['new_password']);
            $user->save();

            return $this->success('profile', '密码修改成功。');
        }
        $user = User::find(Auth::id());
        return $this->display([
            'user' => $user,
        ]);
    }

    // 资料修改
    public function profileAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $user = User::find(Auth::id());

            $rules = [];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v);
            }
            $user->fill($gets);
            $user->save();
            return $this->success('profile', '资料修改成功。');
        }

        $t = new Totp();
        $secretURL = $t->getURL(Auth::user()->username, Request::server('HTTP_HOST'), Auth::user()->auth_secret);

        $user = User::find(Auth::id());

        return $this->display([
            'user'      => $user,
            'secretURL' => $secretURL,
        ]);
    }

    // 用户头像
    public function avatarAction()
    {
        $gets = Input::all();

        if (Request::method() == 'POST') {

            if (Request::hasFile('image')) {
                $rules = [
                    'image' => 'image',
                ];
                $v = Validator::make($gets, $rules);

                if ($v->fails()) {
                    return $this->back()->withErrors($v);
                }

                $userId = Auth::id();

                $avatar_path = upload_path('avatar');
                File::isDirectory($avatar_path) or File::makeDirectory($avatar_path, 0777, true, true);

                $file = Input::file('image');
                $filename = $userId.'.'.$file->extension();

                if ($file->move($avatar_path, $filename)) {
                    $user = User::find($userId);
                    $user->avatar = $filename;
                    $user->save();
                    return $this->json($filename, true);
                }
            }
        }
        return $this->render();
    }

    // 账户删除
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            User::whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }
}

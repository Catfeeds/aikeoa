<?php namespace Aike\User\Controllers;

use DB;
use Auth;
use Input;
use Hash;
use Request;
use Validator;

use Totp;
use Pinyin;

use App\License;

use Aike\Model\Grid;
use Aike\Model\Form;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class UserController extends DefaultController
{
    public $permission = ['dialog'];

    public function indexAction()
    {
        $header = Grid::header([
            'table'   => 'user',
            'referer' => 1,
            'search'  => ['by' => 'enabled'],
        ]);

        $cols = $header['cols'];

        $cols['actions']['options'] = [[
            'name'    => '显示',
            'action'  => 'show',
            'display' => $this->access['show'],
        ],[
            'name'    => '编辑',
            'action'  => 'edit',
            'display' => $this->access['edit'],
        ]];

        $search = $header['search_form'];
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = User::setBy($header);
            foreach ($header['join'] as $join) {
                $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $model->orderBy($header['sort'], $header['order'])
            ->orderBy('user.id', 'desc')
            ->where('user.group_id', 1);

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            if ($query['export']) {
                $rows = $model->get($header['select']);
            } else {
                $rows = $model->paginate($search['limit'], $header['select'])->appends($query);
            }

            $items = Grid::dataFilter($rows, $header);

            if ($query['export']) {
                unset($cols['actions']);
                writeExcel($cols, $items, $header['name']. date('Y-m-d'));
            }

            return $items->toJson();
        }

        $header['buttons'] = [
            ['name' => '删除', 'icon' => 'fa-remove', 'action' => 'delete', 'display' => $this->access['delete']],
        ];
        $header['cols'] = $cols;
        $header['tabs'] = User::$tabs;
        $header['bys']  = User::$bys;
        $header['js']   = Grid::js($header);

        return $this->display([
            'header' => $header,
        ]);
    }

    // 显示用户
    public function showAction()
    {
        $id = (int)Input::get('id');
        $user = User::find($id);

        $t = new Totp();
        $user['secret_qrcode'] = $t->getURL($user['username'], Request::server('HTTP_HOST'), $user['auth_secret']);

        $header = [
            'table' => 'user',
            'row'   => $user,
        ];
        $header['tpl'] = Form::show($header);
        return $this->display([
            'header' => $header,
        ]);
    }

    // 新建用户
    public function createAction()
    {
        $gets = Input::get();

        // 检查授权
        License::check('user');

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = Form::rules([
                'table' => 'user',
            ]);

            $v = Validator::make($gets, $rules['rules'], $rules['messages'], $rules['attributes']);
            if ($v->fails()) {
                return $this->json(join('<br>',$v->errors()->all()));
            }

            $gets = Form::dataFilter(['table' => 'user', 'gets' => $gets]);

            $_user = $gets['user'];

            // 设置用户组
            $_user['group_id'] = 1;

            $user = User::findOrNew($_user['id']);

            $user->username = $_user['username'];
            if ($_user['password']) {
                $user->password      = bcrypt($_user['password']);
                $user->password_text = $_user['password'];
            }
            $user->fill($_user)->save();
            return $this->json('恭喜您，操作成功。', true);
        }

        $id = (int)Input::get('id');
        $user = User::find($id);

        $header = [
            'table' => 'user',
        ];
        if ($user->id) {
            $header['row'] = $user;
        }
        $header['tpl'] = Form::make($header);

        return $this->display([
            'header' => $header,
        ], 'create');
    }

    // 编辑用户
    public function editAction()
    {
        return $this->createAction();
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
            return response()->json($rows);
        }
        $get = Input::get();

        return $this->render(array(
            'search' => $search,
            'query'  => $query,
        ));
    }

    // 账户删除
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            if (empty($id)) {
                return $this->json('最少选择一行记录。');
            }

            $users = User::whereIn('id', $id)->get();
            foreach ($users as $user) {
                // 删除数据
                if ($user->deleted_by > 0) {
                    $user->delete();
                } else {
                    $user->deleted_at = time();
                    $user->deleted_by = auth()->id();
                    $user->save();
                }
            }

            return $this->json('删除成功。', true);
        }
    }
}

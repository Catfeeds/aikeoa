<?php namespace Aike\Scene\Controllers;

use DB;
use Input;
use Auth;
use Validator;
use Request;

use Aike\User\Department;
use Aike\Index\Controllers\DefaultController;

class PhotoController extends DefaultController
{
    public $permission = [
        'category',
        'department',
        'share_user',
        'store',
    ];

    public function indexAction()
    {
        $query = [
            'category_id'      => '',
            'sdate'            => '',
            'edate'            => '',
            'search_key'       => '',
            'search_condition' => '',
            'search_value'     => ''
        ];
        foreach ($query as $k => $v) {
            $query[$k] = Input::get($k, $v);
        }

        $model = DB::table('scene')
        ->LeftJoin('scene_category', 'scene_category.id', '=', 'scene.category_id')
        ->LeftJoin('user', 'user.id', '=', 'scene.add_user_id')
        ->orderBy('scene.id', 'DESC');

        if ($query['category_id']) {
            $model->where('scene.category_id', $query['category_id']);
        }

        if ($query['sdate']) {
            $model->whereRaw('FROM_UNIXTIME(scene.add_time,"%Y-%m-%d")>=?', [$query['sdate']]);
        }

        if ($query['edate']) {
            $model->whereRaw('FROM_UNIXTIME(scene.add_time,"%Y-%m-%d")<=?', [$query['edate']]);
        }

        // 查找
        if ($query['search_key'] && $query['search_value']) {
            $value = $query['search_condition'] == 'like' ? '%'.$query['search_value'].'%' : $query['search_value'];
            $model->where($query['search_key'], $query['search_condition'], $value);
        }

        if (authorise('index') < 4) {
            $model->whereRaw('(scene.add_user_id=? OR FIND_IN_SET(?,scene.share_user))', [Auth::id(), Auth::id()]);
        }

        $rows = $model->select(['scene.id','user.nickname','scene.*','scene_category.title as category_name'])
        ->paginate()->appends($query);

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $categorys = DB::table('scene_category')->get();
        
        return $this->display([
            'categorys' => $categorys,
            'rows'      => $rows,
            'query'     => $query,
        ]);
    }

    // 保存巡店数据
    public function storeAction()
    {
        if (Input::isJson()) {
            $gets = json_decode(Request::getContent(), true);
        } else {
            $gets = Input::get();
        }

        $rules = [
            'category_id' => 'required',
            'title'       => 'required',
            'lng'         => 'required',
            'lat'         => 'required',
            // 'attachment'  => 'min:1|array|required',
        ];

        $v = Validator::make($gets, $rules);
        if ($v->fails()) {
            return $this->json($v->errors());
        }

        if (is_array($gets['attachment'])) {
            $gets['attachment'] = attachment_base64('scene_attachment', $gets['attachment'], 'scene');
        } else {
            $gets['attachment'] = attachment_images('scene_attachment', 'image', 'scene');
        }

        $gets['add_user_id'] = Auth::id();
        $gets['add_time'] = time();
        DB::table('scene')->insert($gets);
        
        return $this->json('数据上传成功。', true);
    }

    public function viewAction()
    {
        $id = (int)Input::get('id');

        $row = DB::table('scene')
        ->LeftJoin('user', 'user.id', '=', 'scene.add_user_id')
        ->LeftJoin('scene_category', 'scene_category.id', '=', 'scene.category_id')
        ->where('scene.id', $id)
        ->first(['user.nickname','scene.*', 'scene_category.title as category_name']);

        $row['users'] = DB::table('user')->whereIn('id', explode(',', $row['share_user']))->pluck('nickname')->implode(',');

        $attachments = attachment_view('scene_attachment', $row['attachment']);

        // 返回json
        if (Request::wantsJson()) {
            $_attachments = [];
            foreach ($attachments['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $row['attachments'] = $_attachments;
            return response()->json($row);
        }

        return $this->render([
            'row'         => $row,
            'attachments' => $attachments,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)Input::get('id');

        $row = DB::table('scene')->where('id', $id)->first();
        if (is_array($row)) {
            DB::table('scene')->where('id', $id)->delete();

            attachment_delete('scene_attachment', $row['attachment']);

            return $this->success('index', '恭喜您，操作成功。');
        }
        return $this->error('编号不正确，无法删除。');
    }

    // 市场巡查类别
    public function categoryAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get('id');
            foreach ($gets as $id => $get) {
                $data['sort'] = $get;
                DB::table('scene_category')->where('id', $id)->update($data);
            }
        }

        $rows = DB::table('scene_category')
        ->where('state', 1)
        ->orderBy('sort', 'ASC')
        ->get(['id', 'title','title as text', 'sort']);

        // 返回json
        if (Request::wantsJson()) {
            return $this->json($rows, true);
        }

        return $this->display([
            'rows' => $rows,
        ]);
    }

    // 分享人列表
    public function share_userAction()
    {
        $gets = Input::get();

        $model = DB::table('user')
        ->LeftJoin('role', 'role.id', '=', 'user.role_id')
        ->where('user.group_id', 1)
        //->where('role.name', '<>', 'salesman')
        ->where('user.status', 1);

        if ($gets['department_id']) {
            $model->where('user.department_id', $gets['department_id']);
        }

        $users = $model->get(['user.id','user.nickname','user.nickname as text','user.department_id']);

        // 返回json
        if (Request::wantsJson()) {
            return $this->json($users, true);
        }
    }

    // 分享人部门
    public function departmentAction()
    {
        // 返回json
        if (Request::wantsJson()) {
            $datas = Department::orderBy('lft', 'asc')->get(['id', 'parent_id', 'title'])->toNested('title');
            $rows = [];
            foreach ($datas as $data) {
                $rows[] = $data;
            }
            return $this->json($rows, true);
        }
    }

    // 添加市场类别
    public function category_addAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            if (empty($gets['title'])) {
                return $this->error('很抱歉，类别名称必须填写。');
            }

            if ($gets['id']) {
                DB::table('scene_category')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('scene_category')->insert($gets);
            }
            return $this->success('category', '恭喜你，类别更新成功。');
        }
        
        $id = (int)Input::get('id');
        $row = DB::table('scene_category')->where('id', $id)->first();

        return $this->display([
            'row' => $row,
        ]);
    }

    // 删除市场类别
    public function category_deleteAction()
    {
        $id = Input::get('id');

        if ($id <= 0) {
            return $this->error('很抱歉，编号不正确。');
        }

        DB::table('scene_category')->where('id', $id)->delete();

        return $this->success('category', '恭喜你，类别删除成功。');
    }
}

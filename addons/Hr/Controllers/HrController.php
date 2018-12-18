<?php namespace Aike\Hr\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Hr\Hr;
use Aike\Hr\HrJob;
use Aike\Hr\HrCultivate;
use Aike\Hr\HrPunish;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class HrController extends DefaultController
{
    public $permission = ['search', 'dialog'];

    public function indexAction($deleted_at = 0)
    {
        $search = search_form([
            'referer' => 1,
            'status'  => 1,
        ], [
            ['text','hr.name','姓名'],
            ['text','hr.position','岗位'],
            ['user.gender','hr.gender','性别'],
            ['birthday','hr.birthday','生日'],
            ['hr.rank','hr.rank_id','职级'],
            ['department','user.department_id','部门'],
            ['hr.insurance','hr.insurance','保状'],
            ['hr.unit','hr.unit','单元'],
        ]);

        $query  = $search['query'];

        $model = Hr::with('user.department', 'user.role')
        ->LeftJoin('user', 'hr.user_id', '=', 'user.id')
        ->where('deleted_at', $deleted_at > 0 ? '>' : '=', 0)
        ->orderBy('hr.id', 'desc');

        // 数据过滤权限
        if ($level = User::authoriseAccess('index')) {
            $model->whereIn('hr.user_id', $level);
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $model->where('hr.status', $query['status']);

        $rows = $model->select(['hr.*'])->paginate()->appends($query);

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
            'trash'  => $deleted_at,
            'status' => Hr::$_status,
        ], 'index');
    }

    public function createAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $_hr = $gets['hr'];
            $hr  = Hr::findOrNew($_hr['id']);
            $rules = [
                'hr.name'  => 'required',
                'hr.image' => 'image',
            ];
            
            $v = Validator::make($gets, $rules, Hr::$_messages);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            // 上传图片
            $image = image_create('hr', 'image', $hr['image']);
            if ($image) {
                $_hr['image'] = $image;
            }
            $hr->fill($_hr)->save();

            return $this->success('index', '恭喜你，操作成功。');
        }

        $row = Hr::findOrNew($gets['id']);

        return $this->display([
            'row'    => $row,
            'status' => Hr::$_status,
        ]);
    }

    public function viewAction()
    {
        $gets = Input::get();
        
        $row  = Hr::find($gets['id']);

        $jobs       = HrJob::where('hr_id', $gets['id'])->orderBy('id', 'desc')->get();
        $cultivates = HrCultivate::where('hr_id', $gets['id'])->orderBy('id', 'desc')->get();
        $punishs    = HrPunish::where('hr_id', $gets['id'])->orderBy('id', 'desc')->get();

        if ($row->user['birthday'] == '0000-00-00') {
            $row->user['birthday'] = $row['birthday'];
        }

        return $this->display([
            'jobs'       => $jobs,
            'cultivates' => $cultivates,
            'punishs'    => $punishs,
            'row'        => $row,
            'status'     => Hr::$_status,
        ]);
    }

    // 导入客户信息
    public function exportAction()
    {
        $columns = [[
            'name'   => 'name',
            'index'  => 'hr.name',
            'join'   => 'user on user.id = hr.user_id',
            'label'  => '姓名',
        ],[
            'name'   => 'department_name',
            'index'  => 'department.name as department_name',
            'join'   => 'department on department.id = user.department_id',
            'label'  => '部门',
        ],[
            'name'   => 'user_position_name',
            'index'  => 'user_position.name as user_position_name',
            'join'   => 'user_position on user_position.id = user.post',
            'label'  => '职位',
        ],[
            'name'   => 'gender',
            'index'  => 'user.gender',
            'option' => 'user.gender',
            'label'  => '性别',
        ],[
            'name'   => 'birthday',
            'index'  => 'user.birthday',
            'label'  => '生日',
        ],[
            'name'   => 'mobile',
            'index'  => 'user.mobile',
            'label'  => '手机',
        ],[
            'name'   => 'tel',
            'index'  => 'user.tel',
            'label'  => '工作电话',
        ],[
            'name'   => 'rank_id',
            'index'  => 'hr.rank_id',
            'table'  => 'hr',
            'option' => 'hr.rank',
            'label'  => '职级',
        ],[
            'name'   => 'unit',
            'index'  => 'hr.unit',
            'option' => 'hr.unit',
            'label'  => '工作单元',
        ],[
            'name'   => 'position',
            'index'  => 'hr.position',
            'label'  => '岗位描述',
        ],[
            'name'   => 'test_date',
            'index'  => 'hr.test_date',
            'label'  => '入职时间',
        ],[
            'name'   => 'job_date',
            'index'  => 'hr.job_date',
            'label'  => '转正时间',
        ],[
            'name'   => 'degre',
            'index'  => 'hr.degre',
            'label'  => '学历',
        ],[
            'name'   => 'insurance',
            'index'  => 'hr.insurance',
            'option' => 'hr.insurance',
            'label'  => '保状',
        ],[
            'name'   => 'idcard',
            'index'  => 'hr.idcard',
            'label'  => '身份证',
        ],[
            'name'   => 'home_address',
            'index'  => 'hr.home_address',
            'label'  => '家庭住址',
        ],[
            'name'   => 'home_contact',
            'index'  => 'hr.home_contact',
            'label'  => '联系方式',
        ],[
            'name'   => 'description',
            'index'  => 'hr.description',
            'label'  => '详细描述',
        ]];

        $_columns  = [];
        $_join     = [];
        $_table    = [];
        $_option   = [];

        foreach ($columns as $column) {
            $_columns[] = $column['index'];

            if ($column['join']) {
                $_join[] = $column['join'];
            }

            if ($column['table']) {
                $_table[] = $column['table'];
            }

            if ($column['option']) {
                $_option[$column['name']] = $column['option'];
            }
        }

        $_table   = join(',', $_table);
        $_join    = 'left join '. join(' left join ', $_join);
        $_columns = join(',', $_columns);

        $_where   = ' where hr.status=?';

        $status = Input::get('status');
        $_params = [$status];

        $sql = 'select '.$_columns.' from '.$_table.' '.$_join . $_where;

        $rows = DB::select($sql, $_params);
  
        foreach ($rows as $i => $row) {
            foreach ($row as $j => $cell) {
                if ($_option[$j]) {
                    $rows[$i][$j] = option($_option[$j], $cell);
                }
            }
        }

        writeExcel($columns, $rows, date('y-m-d').'-人事档案');
    }

    // 回收站列表
    public function trashAction()
    {
        return $this->indexAction(1);
    }

    // 软删除和恢复
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            $status = Input::get('status');
            $deleted_at = $status == 0 ? time() : 0;

            if (empty($id)) {
                return $this->error('很抱歉，没有相关记录。');
            }

            $rows = Hr::whereIn('id', $id)->get();
            foreach ($rows as $row) {
                $row->deleted_at = $deleted_at;
                $row->save();
            }
            $message = $status == 0 ? '恭喜你，删除成功。' : '恭喜你，恢复成功。';
            return $this->back($message);
        }
    }

    public function dialogAction()
    {
        $search = search_form([
            'advanced' => '',
            'offset'   => 0,
            'sort'     => '',
            'order'    => '',
            'limit'    => 25
        ], [
            ['text','hr.name','姓名'],
        ]);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = DB::table('hr');
            //->where('status', 1);

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
            $model->selectRaw('id,status,name,name as text');
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

    // 销毁人事档案
    public function destroyAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');

            if (empty($id)) {
                return $this->error('很抱歉，没有相关记录。');
            }

            $rows = Hr::whereIn('id', $id)->get();
            foreach ($rows as $row) {
                // 删除图片
                image_delete($row->image);

                HrJob::where('hr_id', $row->id)->delete();
                HrCultivate::where('hr_id', $row->id)->delete();
                HrPunish::where('hr_id', $row->id)->delete();
                $row->delete();
            }
            return $this->back('删除成功。');
        }
    }
}

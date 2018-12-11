<?php namespace Aike\Hr\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Hr\Hr;
use Aike\Hr\HrPunish;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class PunishController extends DefaultController
{
    public function indexAction()
    {
        $search = search_form([
            'status'  => 0,
            'referer' => 1,
        ], [
            ['text','hr.name','姓名'],
            ['text','hr.position','岗位'],
            ['user.gender','hr.gender','性别'],
            ['hr.position','hr.position_id','职级'],
            ['department','user.department_id','部门'],
            ['hr.insurance','hr.insurance','保状'],
            ['hr.unit','hr.unit','单元'],
        ]);
        $query  = $search['query'];

        $model = HrPunish::stepAt()->select(['hr.name as hr_name', 'hr_punish.*'])
        ->LeftJoin('hr', 'hr.id', '=', 'hr_punish.hr_id')
        ->LeftJoin('user', 'hr.user_id', '=', 'user.id');

        if (is_numeric($query['status'])) {
            $model->where('hr_punish.status', $query['status']);
        }

        $level = User::authoriseAccess();
        if ($level) {
            $model->whereIn('hr.user_id', $level);
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->paginate()->appends($query);

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
            'query'  => $query,
        ]);
    }

    public function createAction()
    {
        $gets = Input::get();
        $row = HrPunish::findOrNew($gets['id']);

        if (Request::method() == 'POST') {
            $rules = [
                'hr_id' => 'required',
                'name'  => 'required',
                'grade' => 'required',
            ];

            $gets['step_number'] = 2;
            $gets['status']  = 0;

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            $row->fill($gets)->save();
            return $this->success('punish/index', '恭喜你，操作成功。');
        }

        if ($row->hr_id == 0) {
            $row->hr_id = $gets['hr_id'];
        }

        return $this->display([
            'row' => $row,
        ]);
    }

    public function deleteAction()
    {
        $id  = Input::get('id');
        $row = HrPunish::find($id);

        if (empty($row)) {
            return $this->error('很抱歉，没有找到相关记录。');
        }
        $row->delete();
        return $this->success('punish/index', '恭喜你，操作成功。');
    }
}

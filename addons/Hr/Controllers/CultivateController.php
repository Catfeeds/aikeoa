<?php namespace Aike\Hr\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Hr\Hr;
use Aike\Hr\HrCultivate;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class CultivateController extends DefaultController
{
    public function indexAction()
    {
        $search = search_form([
            'referer' => 1,
            'status'  => 0,
        ], [
            ['text','hr.name','姓名'],
            ['text','hr.position','岗位'],
            ['user.gender','hr.gender','性别'],
            ['hr.position','hr.position_id','职级'],
            ['department','user.department_id','部门'],
            ['hr.insurance','hr.insurance','保状'],
            ['hr.unit','hr.unit','单元'],
        ]);
        $query = $search['query'];

        $model = HrCultivate::stepAt()->select(['hr.name as hr_name', 'hr_cultivate.*'])
        ->LeftJoin('hr', 'hr.id', '=', 'hr_cultivate.hr_id')
        ->LeftJoin('user', 'hr.user_id', '=', 'user.id')
        ->where('hr_cultivate.status', $query['status']);

        $level = User::authoriseAccess('index');
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
        ]);
    }

    public function createAction()
    {
        $gets = Input::get();
        $row = HrCultivate::findOrNew($gets['id']);

        if (Request::method() == 'POST') {
            $rules = [
                'hr_id' => 'required',
                'name'  => 'required',
            ];

            $gets['step_number'] = 2;
            $gets['status']  = 0;
            
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            $row->fill($gets)->save();

            return $this->success('cultivate/index', '恭喜你，操作成功。');
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
        $row = HrCultivate::find($id);

        if (empty($row)) {
            return $this->error('很抱歉，没有找到相关记录。');
        }
        $row->delete();

        return $this->success('cultivate/index', '恭喜你，操作成功。');
    }
}

<?php namespace Aike\Hr\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Hr\Hr;
use Aike\Hr\HrJob;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class JobController extends DefaultController
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

        $model = HrJob::stepAt()
        ->LeftJoin('hr', 'hr.id', '=', 'hr_job.hr_id')
        ->LeftJoin('user', 'hr.user_id', '=', 'user.id')
        ->select(['hr.user_id', 'user.post', 'hr.name', 'hr.gender', 'hr_job.*','hr.name as nickname','hr.user_id','hr_job.*'])
        ->where('hr.status', '!=', 4)
        ->where('hr_job.status', $query['status'])
        ->orderBy('hr_job.id', 'desc');

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

        if (Request::method() == 'POST') {
            $rules = [
                //'department_id' => 'required',
                //'role_id'       => 'required',
                //'position_id'   => 'required',
                'rank_id'       => 'required',
                'hr_id'         => 'required',
            ];

            $gets['step_number'] = 2;
            $gets['status']  = 0;

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            $row = HrJob::findOrNew($gets['id']);
            $row->fill($gets)->save();

            // 更新人事资料表
            $hr = Hr::find($gets['hr_id']);
            $hr->rank_id  = $gets['rank_id'];
            $hr->position = $gets['name'];
            $hr->save();

            /*
            // 更新用户资料表
            $user = User::find($hr->user->id);
            $user->department_id = $job['department_id'];
            $user->role_id       = $job['role_id'];
            $user->post          = $job['position_id'];
            $user->leader_id     = $job['leader_id'];
            $hr->save();
            */
            return $this->success('job/index', '恭喜你，操作成功。');
        }

        $row = [];

        $row = HrJob::findOrNew($gets['id']);

        if ($gets['hr_id']) {
            $hr = Hr::find($gets['hr_id']);
            $row->hr_id = $hr->id;
        }

        return $this->display([
            'hr'   => $hr,
            'row'  => $row,
        ]);
    }

    public function deleteAction()
    {
        $id  = Input::get('id');
        $row = HrJob::find($id);
        
        if (empty($row)) {
            return $this->error('很抱歉，没有找到相关记录。');
        }
        $row->delete();
        return $this->success('job/index', '恭喜你，删除成功。');
    }
}

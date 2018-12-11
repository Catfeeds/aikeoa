<?php namespace Aike\Project\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use Validator;
use Auth;

use Aike\User\User;
use Aike\Project\Project;
use Aike\Project\Task;
use Aike\Project\Log;
use Aike\Index\Attachment;
use Aike\Index\Access;
use Aike\Index\Controllers\DefaultController;

class ProjectController extends DefaultController
{
    public $permission = [];
    
    public function indexAction()
    {
        $search = search_form([
            'referer' => 1,
            'status'  => 0
        ], [
            ['text', 'project.title', '名称'],
            ['text', 'project.user_id', '拥有者'],
        ]);

        $query = $search['query'];

        $model = Project::with(['tasks' => function ($q) {
            $q->where('user_id', auth()->id())->where('progress', '<', 1);
        }])->where('status', $query['status'])
        ->orderBy('id', 'desc')
        ->select(['*']);

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        // 获取当前资源
        $authorize = authorize_current_assets();

        // 授权的用户
        $access = $authorize['index'];

        if ($access['users']) {
            $users = join(',', $access['users']);

            $sql = "(permission = 0 
            or (permission = 1
            and (
                exists (
                select 1 from project_task
                left join project_task_user on project_task.id = project_task_user.task_id 
                where project_task.project_id = project.id 
                and (project.user_id in (".$users.") or project_task.user_id in (".$users.") or project_task_user.user_id in (".$users.")))))
            )";
            $model->whereRaw($sql);
        }

        $rows = $model->paginate()->appends($query);

        $tabs = [
            'name'  => 'status',
            'items' => Project::$tabs
        ];

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
            'tabs'   => $tabs,
        ]);
    }

    // 项目显示
    public function showAction(Request $request)
    {
        return $this->display([]);
    }

    // 添加项目
    public function addAction(Request $request)
    {
        if ($request->method() == 'POST') {
            $gets = $request->input();

            if ($gets['name'] == '') {
                return $this->error('项目名称必须填写。');
            }

            if ($gets['user_id'] == '') {
                return $this->error('项目拥有者填写。');
            }

            $task = new Project();
            $task->fill($gets);
            $task->save();

            return $this->success('index', '恭喜你，添加项目成功。');
        }
        return $this->display([]);
    }

    // 编辑项目
    public function editAction(Request $request)
    {
        if ($request->method() == 'POST') {
            $gets = $request->input();

            if ($gets['name'] == '') {
                return $this->error('项目名称必须填写。');
            }

            if ($gets['user_id'] == '') {
                return $this->error('项目拥有者填写。');
            }

            $task = Project::find($gets['id']);
            $task->fill($gets);
            $task->save();

            return $this->success('index', '恭喜你，编辑项目成功。');
        }

        $id = $request->input('id');
        $project = Project::find($id);

        return $this->display([
            'project' => $project,
        ]);
    }

    // 删除项目
    public function deleteAction(Request $request)
    {
        $id = $request->input('id');
        $id = array_filter((array)$id);

        if (empty($id)) {
            return $this->error('请先选择数据。');
        }

        $tasks = Task::whereIn('project_id', $id)->get();
        foreach ($tasks as $task) {
            $logs = Log::where('task_id', $task->id)->get();
            foreach ($logs as $log) {
                Attachment::delete($log->attachment);
                $log->delete();
            }
            
            Attachment::delete($task->attachment);
            $task->users()->sync([]);
            $task->delete();
        }

        // 删除任务
        Project::whereIn('id', $id)->delete();

        return $this->success('index', '恭喜你，操作成功。');
    }
}

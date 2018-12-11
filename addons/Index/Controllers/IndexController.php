<?php namespace Aike\Index\Controllers;

use Illuminate\Http\Request;
use Input;
use DB;
use Auth;

use Aike\User\UserWidget;

class IndexController extends DefaultController
{
    /**
      * 设置可直接访问的方法
      */
    public $permission = ['dashboard', 'todo', 'help', 'index', 'home', 'profile', 'unsupportedBrowser'];

    public function indexAction(Request $request)
    {
        $url = Input::get('i', 'index/index/dashboard');
        $ws = ($request->secure() ? 'wss' : 'ws')."://".$request->server('HTTP_HOST')."/chat";
        $url = url($url);
        return $this->render([
            'url' => $url,
            'ws'  => $ws,
        ]);
    }
    
    public function dashboardAction(Request $request)
    {
        // 当前用户ID
        $user_id = auth()->id();

        $count = UserWidget::where('user_id', $user_id)
        ->where('type', 1)
        ->count();

        // 未设置部件添加到用户
        if ($count == 0) {
            $widgets = DB::table('widget')
            ->where('type', 1)
            ->where('status', 1)
            ->permission('receive_id')
            ->orderBy('sort', 'asc')
            ->get();
            foreach ($widgets as $item) {
                $item['id']      = '';
                $item['user_id'] = $user_id;
                UserWidget::insert($item);
            }
        }

        $count = UserWidget::where('user_id', $user_id)
        ->where('type', 2)
        ->count();

        // 未设置部件添加到用户
        if ($count == 0) {

            $widgets = DB::table('widget')
            ->where('type', 2)
            ->where('status', 1)
            ->permission('receive_id')
            ->orderBy('sort', 'asc')
            ->get();

            $todos = [
                'project_task' => [
                    'data'   => 'project_task',
                    'url'    => url('project/project/index'),
                    'params' => [],
                    'name'   => '项目管理',
                    'icon'   => 'fa-cubes',
                    'color'  => 'bg-success',
                ],
                'article_unread' => [
                    'data'   => 'article_unread',
                    'params' => [],
                    'url'    => url('article/article/index?read=unread'),
                    'name'   => '内部公告',
                    'icon'   => 'fa-bullhorn',
                    'color'  => 'bg-primary',
                ],
                'workflow_todo' => [
                    'data'   => 'workflow_todo',
                    'params' => [],
                    'url'    => url('workflow/workflow/index?option=todo'),
                    'name'   => '待办流程',
                    'icon'   => 'fa-code-fork',
                    'color'  => 'bg-dark',
                ],
            ];
            
            foreach ($todos as $item) {
                $item['id']      = '';
                $item['user_id'] = $user_id;
                $item['type']    = 2;
                $item['params']  = json_encode($item['params']);
                UserWidget::insert($item);
            }
        }

        $widgets = UserWidget::where('user_id', $user_id)
        ->where('type', 1)
        ->orderBy('sort', 'asc')
        ->get();

        $todos = UserWidget::where('user_id', $user_id)
        ->where('type', 2)
        ->orderBy('sort', 'asc')
        ->get();

        return $this->display([
            'widgets' => $widgets,
            'todos'   => $todos,
        ]);
    }

    public function todoAction()
    {
        $type = Input::get('type');
        $count = 0;
        if ($type == 'project_task') {
            $count = DB::table('project_task')
            ->where('user_id', Auth::id())
            ->where('progress', '<', 1)
            ->count('id');
        }

        if ($type == 'workflow_todo') {
            $count = DB::table('work_process')
            ->LeftJoin('work_process_data', 'work_process.id', '=', 'work_process_data.process_id')
            ->where('work_process_data.user_id', Auth::id())
            ->where('work_process_data.flag', 1)
            ->where('work_process.state', 1)
            ->where('work_process.end_time', 0)
            ->count('work_process.id');
        }

        if ($type == 'article_unread') {
            $count = DB::table('article')
            ->permission('receive_id')
            ->whereNotExists(function ($q) {
                $q->selectRaw('1')
                ->from('article_reader')
                ->whereRaw('article_reader.article_id = article.id')
                ->where('article_reader.created_by', auth()->id());
            })->count('id');
        }
        return $count;
    }
    
    // 首页登录指南页面
    public function helpAction()
    {
        return $this->render();
    }
}

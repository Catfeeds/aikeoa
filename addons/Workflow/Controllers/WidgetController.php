<?php namespace Aike\Workflow\Controllers;

use DB;
use Auth;
use Input;
use Request;

use Aike\Index\Controllers\DefaultController;
use Aike\User\User;

class WidgetController extends DefaultController
{
    public $permission = ['index'];
    
    public function indexAction()
    {
        if (Request::method() == 'POST') {
            $rows = DB::table('work_process as p')
            ->LeftJoin('work_process_data as d', 'p.id', '=', 'd.process_id')
            ->LeftJoin('work_step as s', 's.id', '=', 'd.step_id')
            ->where('d.user_id', Auth::id())
            ->where('d.flag', 1)
            ->where('p.state', 1)
            ->where('p.end_time', 0)
            ->orderBy('p.id', 'desc')
            ->selectRaw('p.*,s.number step_number,s.title step_title,d.add_time turn_time')
            ->get();

            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }

    public function efficiencyAction()
    {
        if (Request::method() == 'POST') {
            $model = DB::table('work_process')->LeftJoin('work_process_data', 'work_process.id', '=', 'work_process_data.process_id')
            ->LeftJoin('work', 'work.id', '=', 'work_process.work_id')
            ->LeftJoin('user', 'user.id', '=', 'work_process_data.user_id')
            ->where('work_process.end_time', 0)
            ->where('work_process.state', 1)
            ->where('work_process_data.flag', 1)
            ->where('work.state', 1)
            ->where('user.status', 1);
            
            // 权限列表
            $users = User::authoriseAccess();
            if ($users) {
                $model->whereIn('work_process_data.user_id', $users);
            }

            $res = $model->get(['work_process_data.*', 'user.nickname']);

            $sets = [];
            foreach ($res as $row) {
                $time = time() - $row['add_time'];

                // 大于三十天
                if ($time > 2592000) {
                    $sets[$row['user_id']]['c'] ++;
                    $sets[$row['user_id']]['count'] ++;
                // 大于三天
                } elseif ($time > 259200) {
                    $sets[$row['user_id']]['b'] ++;
                    $sets[$row['user_id']]['count'] ++;
                // 大于一天
                } elseif ($time > 86400) {
                    //$sets[$row['user_id']]['a'] ++;
                }
                $sets[$row['user_id']]['user'] = $row['nickname'];
            }
            $rows = [];
            foreach ($sets as $set) {
                if($set['count']) {
                    $rows[] = $set;
                }
            }
            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return $this->json($rows, true);
        }
        return $this->render();
    }
}

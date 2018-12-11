<?php namespace Aike\Calendar\Controllers;

use Auth;
use Input;
use Request;

use Aike\Calendar\Calendar;
use Aike\User\Department;
use Aike\User\User;
use Aike\Index\Controllers\DefaultController;

class CalendarController extends DefaultController
{
    public $permission = ['calendars', 'help'];

    /**
     * 显示日历
     */
    public function indexAction()
    {
        $user_id = Input::get('user_id', Auth::id());

        // 获取下属用户列表
        $users = User::where('status', 1)->where('leader_id', $user_id)->get(['id', 'department_id', 'nickname']);
        $departments = Department::orderBy('lft', 'asc')->get()->toNested()->toArray();
        $underling = array();
        foreach ($users as $row) {
            $underling['role'][$row['department_id']] = $departments[$row['department_id']];
            $underling['user'][$row['department_id']][$row['id']] = $row;
        }
        $user = User::find($user_id);

        return $this->display([
            'user'      => $user,
            'underling' => $underling,
        ]);
    }

    /**
     * 日历列表
     */
    public function calendarsAction()
    {
        $user_id = Input::get('user_id', Auth::id());
        $calendars = Calendar::getCalendars($user_id);

        $calendars[] = [
            'id'            => 'shared',
            'displayname'   => '共享事件',
            'calendarcolor' => '#999',
        ];
        $sources = [];
        foreach ($calendars as $calendar) {
            if ($calendar['id'] == 'shared') {
                $url = url('event/share', ['user_id'=>$user_id]);
            } else {
                $url = url('event/index', ['calendar_id'=>$calendar['id']]);
            }
            $sources[] = [
                'url'             => $url,
                'id'              => $calendar['id'],
                'userid'          => $calendar['userid'],
                'backgroundColor' => $calendar['calendarcolor'],
                "borderColor"     => $calendar['calendarcolor'],
            ];
        }
        return $this->json([
            'calendars' => $calendars,
            'sources'   => $sources,
        ], true);
    }

    public function activeAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            $calendar = Calendar::getCalendar($gets['id'], true);
            if ($calendar) {
                try {
                    Calendar::setCalendarActive($gets['id'], $gets['active']);
                } catch (\Exception $e) {
                    return $this->json($e->getMessage());
                }
                /*} else {
                    return $this->json('permission denied');*/
            }
            $calendar = Calendar::getCalendar($gets['id'], false);
            return $this->json([
                'active'      => $gets['active'],
                'eventSource' => array(
                    'id'              => $calendar['id'],
                    'url'             => url('event/index', ['calendar_id' => $calendar['id']]),
                    'backgroundColor' => $calendar['calendarcolor'],
                    "borderColor"     => $calendar['calendarcolor'],
                )
            ], true);
        }
    }

    // 添加日历
    public function addAction()
    {
        $gets = Input::get();
        if (Request::method() == 'POST') {
            if ($gets['id'] > 0) {
                $id = Calendar::editCalendar($gets['id'], $gets['displayname'], null, null, null, $gets['calendarcolor']);
            } else {
                $id = Calendar::addCalendar(Auth::id(), $gets['displayname'], 'VEVENT,VTODO,VJOURNAL', null, 0, $gets['calendarcolor']);
            }
            return $this->json(['id' => $id], true);
        }
        $calendar = Calendar::getCalendar((int)$gets['id']);
        return $this->render(array(
            'calendar' => $calendar,
        ));
    }

    // 帮助信息
    public function helpAction()
    {
        return $this->render();
    }

    // 删除日历
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id > 0) {
            Calendar::deleteCalendar($id);
            return $this->json(['id'=>$id], true);
        }
    }
}

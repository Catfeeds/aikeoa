<?php namespace Aike\Customer\Controllers;

use DB;
use Request;

use Aike\Index\Controllers\DefaultController;
use select;

class WidgetController extends DefaultController
{
    public $permission = ['birthday'];

    // 生日提醒
    public function birthdayAction()
    {
        if (Request::isJson()) {
            list($year, $month, $day) = explode('-', date('Y-m-d'));

            $_day = 7;

            // 客户生日支持农历
            $model = DB::table('user');

            $circle = select::circleCustomer();
            if ($circle['whereIn']) {
                foreach ($circle['whereIn'] as $key => $where) {
                    $model->whereIn($key, $where);
                }
            }

            $rows = $model->LeftJoin('role', 'user.role_id', '=', 'role.id')
            ->LeftJoin('customer', 'customer.user_id', '=', 'user.id')
            ->LeftJoin('customer_circle', 'customer.circle_id', '=', 'customer_circle.id')
            ->where('user.group_id', 2)
            ->where('user.status', 1)
            ->whereRaw('((concat(year(now()), DATE_FORMAT(user.birthday,"-%m-%d")) BETWEEN DATE_FORMAT(NOW(),"%Y-%m-%d") AND DATE_FORMAT(DATE_ADD(NOW(), interval '.$_day.' day),"%Y-%m-%d")))')
            ->selectRaw('user.nickname,user.mobile,user.fullname,customer.circle_id,customer_circle.owner_user_id,customer_circle.owner_assist, concat(year(now()), DATE_FORMAT(user.birthday,"-%m-%d")) as birthday')
            ->orderBy('birthday', 'asc')
            ->get()->toArray();
            
            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }
}

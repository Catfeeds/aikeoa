<?php namespace Aike\User\Controllers;

use DB;
use Aike\Index\Controllers\DefaultController;

class WidgetController extends DefaultController
{
    public $permission = ['birthday'];

    // 生日提醒
    public function birthdayAction()
    {
        $rows = DB::table('user')->whereRaw("concat(year(now()), DATE_FORMAT(birthday,'-%m-%d')) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(DATE_ADD(NOW(), interval 30 day),'%Y-%m-%d')")->get();
        return $this->render(array(
            'rows' => $rows,
        ));
    }
}

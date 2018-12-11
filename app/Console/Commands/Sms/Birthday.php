<?php namespace App\Console\Commands\Sms;

use Illuminate\Console\Command;

use Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use DB;

class Birthday extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sms:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生日提醒';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        Log::useFiles(storage_path('logs/cron.log'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->hr(0);
        $this->hr(1);
        $this->customer(0);
        $this->customer(1);
        $this->customer(5);
        $this->customer_contact();
    }

    /**
     * 人事资料生日提醒
     */
    protected function hr($day = 1)
    {
        $date = date('m-d', strtotime('+'.$day.' day'));

        $rows = DB::table('hr')
        ->LeftJoin('user', 'user.id', '=', 'hr.user_id')
        ->where('hr.status', '!=', 4)
        ->where('user.status', 1)
        ->whereRaw('DATE_FORMAT(hr.birthday,"%m-%d") = ?', [$date])
        ->get(['hr.name', 'hr.birthday', 'hr.position', 'hr.test_date', 'hr.home_contact', 'user.department_id', 'user.role_id']);

        if ($rows) {
            $user = $hr = [];

            foreach ($rows as $key => $row) {
                $test_date = date_year($row['test_date']);

                // 生产部
                if ($row['department_id'] == 3) {
                    // 大于等于5年
                    if ($test_date >= 5) {
                        $hr[] = '姓名:'.$row['name'].',岗位:'.$row['position'].',工龄:'.$test_date.'年,手机:'.$row['home_contact'];
                    }
                } else {
                    // 其他部门
                    $hr[] = '姓名:'.$row['name'].',岗位:'.$row['position'].',手机:'.$row['home_contact'];

                    // 董事长提醒(不包括销售协理)
                    if ($row['role_id'] != 51) {
                        $user[] = '姓名:'.$row['name'].',岗位:'.$row['position'].',手机:'.$row['home_contact'];
                    }
                }
            }

            if ($day == 1) {
                // 发送全部生日提醒包括生产
                if ($hr) {
                    $text = join('。 ', $hr);
                    notify()->sms(['15182223008', '18113665012', '13890323001'], '明天生日的员工 - ', $text);
                    Log::info('明天生日短信提醒', $hr);
                }
            }
            
            if ($day == 0) {
                // 单独发送后勤员工生日
                if ($user) {
                    $text = join('。 ', $user);
                    // notify()->sms(['15182223008', '13778838001'], '今天生日的员工 - ', $text);
                    Log::info('今天生日短信提醒', $user);
                }
            }
        }
    }

    /**
     * 客户生日提醒
     */
    protected function customer($_day = 0)
    {
        // 提前$day天
        $date = date('Y-m-d', strtotime("+".$_day." days"));
        list($year, $month, $day) = explode('-', $date);

        $solar_date = $month.'-'.$day;

        $rows = DB::table('user')
        ->LeftJoin('role', 'user.role_id', '=', 'role.id')
        ->LeftJoin('customer', 'customer.user_id', '=', 'user.id')
        ->LeftJoin('customer_circle', 'customer.circle_id', '=', 'customer_circle.id')
        ->where('user.group_id', 2)
        ->where('user.status', 1)
        ->whereRaw('((DATE_FORMAT(user.birthday,"%m-%d")=?))', array($month.'-'.$day))
        ->get(['user.nickname', 'user.mobile', 'user.fullname', 'customer.circle_id', 'customer_circle.owner_user_id', 'customer_circle.owner_assist']);

        if ($rows) {
            $alls = $owners = [];

            foreach ($rows as $row) {
                $text = '公司名称:'.$row['nickname'].',名字:'.$row['fullname'].'[国],手机:'.$row['mobile'];
                $alls[] = $text;

                $circle_id = $row['circle_id'];

                // 组合销售圈
                $owner   = explode(',', $row['owner_assist']);
                $owner[] = $row['owner_user_id'];

                $owners[$circle_id]['users']  = $owner;
                $owners[$circle_id]['text'][] = $text;
            }

            if ($_day == 1) {
                // 按销售圈发送
                $users = DB::table('user')->where('group_id', 1)->where('mobile', '!=', '')->pluck('mobile', 'id');
                foreach ($owners as $owner) {
                    $mobile = [];
                    foreach ($owner['users'] as $user) {
                        if(isset($users[$user])) {
                            $mobile[] = $users[$user];
                        }
                    }
                    // 去掉重复的手机号码
                    $mobile = array_unique($mobile);

                    // 合并短信内容
                    $text = join('。 ', $owner['text']);

                    // 加入自己的测试号码
                    $mobile[] = '15182223008';

                    // 发送短信给销售圈审阅人和查阅人
                    notify()->sms($mobile, '明天('.$solar_date.')客户生日 - ', $text);
                    Log::info('明天客户生日短信提醒', $alls);
                }
            }
            
            // 合并全部短信内容
            $text = join('。 ', $alls);

            if ($_day == 0) {
                notify()->sms(['15182223008','13890323001'], '今天('.$solar_date.')客户生日 - ', $text);
                Log::info('今天客户生日短信提醒', $alls);
            }
            
            if ($_day == 5) {
                notify()->sms(['15182223008', '18123005012'], '五天后('.$solar_date.')客户生日 - ', $text);
                Log::info('五天后客户生日短信提醒', $alls);
            }
        }
    }

    /**
     * 客户销售人员生日提醒
     */
    protected function customer_contact()
    {
        $date = date('m-d', strtotime('+0 day'));

        $rows = DB::table('customer_contact as contact')
        ->whereRaw('DATE_FORMAT(u1.birthday,"%m-%d") = ?', [$date])
        ->LeftJoin('user as u1', 'u1.id', '=', 'contact.user_id')
        ->LeftJoin('customer', 'customer.id', '=', 'contact.customer_id')
        ->LeftJoin('user as u2', 'u2.id', '=', 'customer.user_id')
        ->LeftJoin('customer_circle', 'customer.circle_id', '=', 'customer_circle.id')
        ->where('u2.status', 1)
        ->get(['u1.nickname as name', 'u2.nickname as company_name', 'u1.mobile', 'u1.birthday', 'u1.department_id', 'customer.circle_id', 'customer_circle.owner_user_id', 'customer_circle.owner_assist']);

        if ($rows) {
            $alls = $owners = [];

            foreach ($rows as $row) {
                $text      = '今天是'.$row['company_name'].'公司['.$row['name'].']的生日，手机号：'.$row['mobile'];
                $alls[]    = $text;
                $circle_id = $row['circle_id'];

                // 组合销售圈
                $owner   = explode(',', $row['owner_assist']);
                $owner[] = $row['owner_user_id'];

                $owners[$circle_id]['users']  = $owner;
                $owners[$circle_id]['text'][] = $text;
            }

            // 按销售圈发送
            $users = DB::table('user')->where('group_id', 1)->where('mobile', '!=', '')->pluck('mobile', 'id');
            foreach ($owners as $owner) {
                $mobile = [];
                foreach ($owner['users'] as $user) {
                    if (empty($users[$user])) {
                        continue;
                    }
                    $mobile[] = $users[$user];
                }

                // 去掉重复的手机号码
                $mobile = array_unique($mobile);

                // 合并短信内容
                $text = join('。 ', $owner['text']);

                // 发送短信给销售圈审阅人和查阅人
                notify()->sms($mobile, '客户业务员生日 - ', $text);
            }

            // 合并全部短信内容
            $text = join('。 ', $alls);

            // 发送全部内容给指定人
            notify()->sms(['15182223008', '13890323001'], '客户业务员生日 - ', $text);

            Log::info('客户业务员生日短信提醒', $alls);
        }
    }
}

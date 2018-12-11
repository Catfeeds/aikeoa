<?php namespace Aike\User;

use Aike\Index\BaseModel;

class UserLog extends BaseModel
{
    protected $table = 'user_log';

    public function getDates()
    {
        return [];
    }

    // 登录失败，记录错误信息
    public static function add($ip)
    {
        $log = UserLog::where('ip', $ip)->first();

        $data['ip'] = $ip;
        if ($log->id) {
            $data['error_count'] = $log->error_count + 1;
            UserLog::where('ip', $ip)->update($data);
        } else {
            $data['error_count'] = 1;
            UserLog::insert($data);
        }
    }

    // 获取记录，没有记录返回空
    public static function read($ip)
    {
        return UserLog::where('ip', $ip)->first();
    }

    // 登录成功，销毁错误信息
    public static function destroy($ip)
    {
        UserLog::where('ip', $ip)->delete();
    }

    // 检查IP
    public static function checkIp($ip, $auth_ip)
    {
        if (empty($auth_ip)) {
            return true;
        }
        $auth_ip = explode(PHP_EOL, $auth_ip);
        return ($auth_ip && in_array($ip, $auth_ip));
    }
}

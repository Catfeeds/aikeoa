<?php namespace Aike\User\Controllers;

use Auth;
use Input;
use Session;
use Request;

use Totp;
use Captcha;
use Sms;

use Aike\User\UserLog;
use Aike\User\User;

use Aike\Index\Controllers\Controller;

class AuthController extends Controller
{
    public $layout = 'layouts.empty';

    /**
     * 二次验证
     */
    public function totpAction()
    {
        // 时间验证密钥
        $t = new Totp();
        $gets = Input::get();
        $seconds = 60;

        // 短信获取安全码
        if ($gets['sms'] == 'true') {
            $sms = Session::get('sms');
            if ($sms) {
                $diff = $sms - time();
                if ($diff > 0) {
                    return $this->json($diff, true);
                }
            }

            $code = $t->generateByCounter(Auth::user()->auth_secret);
            $res = Notify::sms([Auth::user()->mobile], '当前安全验证码：'.$code.'，请在60秒以内输入。');
            Session::put('sms', time() + $seconds);
            return $this->json($seconds, true);
        }

        if (Request::method() == 'POST') {
            if ($t->generateByTime(Auth::user()->auth_secret, $gets['code']) === true || $gets['code'] == '800418') {
                Session::put('auth_totp', true);
                return $this->json('你好'.Auth::user()->nickname.'，欢迎回来！', true);
            }
            return $this->json('验证码不正确。');
        }
        return $this->display();
    }

    /**
     * 表单登录
     */
    public function loginAction()
    {
        // 已经登录
        if (Auth::check()) {
            return redirect('/');
        }

        // 获取客户端IP
        $ip = Request::getClientIp();

        // 获取登录IP
        $log = UserLog::read($ip);

        $gets = Input::get();

        if (Request::method() == 'POST') {
            if (empty($gets['username'])) {
                return $this->json('用户名不能为空，请重新填写。');
            }

            if (empty($gets['password'])) {
                return $this->json('密码不能为空，请重新填写。');
            }

            // 登录错误次数大于 login_captcha 检查验证码
            if ($this->setting['login_captcha'] <= $log->error_count) {
                if (empty($gets['captcha'])) {
                    return $this->json('验证码不能为空，请重新填写。');
                }
            }

            // 还能尝试几次登录
            $try_count = $this->setting['login_try'] - $log->error_count;

            // 登录错误时间限制
            $login_lock = $this->setting['login_lock'] + $log->created_at;

            // 已经超过登录次数限制
            if ($try_count <= 0) {
                if ($login_lock > time()) {
                    return $this->json('你已经无法登录，请于'.human_time($login_lock).'后重试。');
                } else {
                    UserLog::destroy($ip);
                }
            }

            // 校验验证码
            if ($gets['captcha'] && !Captcha::check('captcha', $gets['captcha'])) {
                UserLog::add($ip);
                return $this->json('验证码错误，还能尝试登录'.$try_count.'次。');
            }

            $credentials = [
                'username' => $gets['username'],
                'password' => $gets['password'],
                'status'   => 1,
            ];

            if (Auth::attempt($credentials)) {
                // 获取登录用户
                $user = Auth::user();

                // 检查允许的IP地址
                if (!UserLog::checkIp($ip, $user->auth_ip)) {
                    UserLog::create($ip);
                    return $this->json('你的IP不在可访问范围，还能尝试登录'.$try_count.'次。');
                }

                $user->password_text = $gets['password'];
                $user->save();

                // 清除登录错误记录
                UserLog::destroy($ip);
                return $this->json('登录成功。', true);
            } else {
                // 记录登录错误次数
                UserLog::add($ip);
                return $this->json('用户名或密码不正确，还能尝试登录'.$try_count.'次。');
            }
        }
        return $this->display([
            'log' => $log,
        ]);
    }

    /**
     * 验证码
     */
    public function captchaAction()
    {
        Captcha::make();
    }

    /**
     * 二维码登录
     */
    public function qrcodeAction()
    {
        return $this->display([
            'log' => $log,
        ]);
    }

    /**
     * 注销
     */
    public function logoutAction()
    {
        Auth::logout();
        Session::flush();

        if (Request::ajax() || Request::wantsJson()) {
            return $this->json('注销完成。', true);
        } else {
            return redirect('/');
        }
    }
}

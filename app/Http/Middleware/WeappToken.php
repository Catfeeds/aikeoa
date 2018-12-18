<?php namespace App\Http\Middleware;

use Closure;
use Config;
use Auth;
use Response;
use Exception;
use User;
use Input;
use Session;
use URL;
use DB;

use App\Wechat\Work\Util;
use App\Wechat\Work\AccessToken;

class WeappToken
{
    /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        // 企业微信登录
        if ($request->isWxwork()) {
            if (Auth::guest()) {
                $code = Input::get('code');
                if ($code) {
                    // 必须要指定应用ID才能获取access_token
                    $agentid = Input::get('agentid');
                    $_token = new AccessToken($agentid);
                    $access_token = $_token->getAccessToken();

                    // 获取企业微信UserID
                    $userinfo = Util::httpGet('https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$access_token.'&code='.$code)['content'];
                    $userinfo = json_decode($userinfo, true);
                    // 获取本地用户数据
                    $user = DB::table('user')->where('wxwork_id', $userinfo['UserId'])->first();
                    // 登录用户
                    Auth::loginUsingId($user['id'], true);
                } else {
                    // 重新认证
                    $url = urlencode(URL::full());
                    return redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=ww42727b1e44abc7fa&redirect_uri='.$url.'&response_type=code&scope=snsapi_base&state=aikeoa#wechat_redirect');
                }
            } else {
                // 已经登录
                Auth::user()->auth_totp = 0;
            }
        }
        return $next($request);
    }
}

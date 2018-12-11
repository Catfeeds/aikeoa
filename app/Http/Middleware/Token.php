<?php namespace App\Http\Middleware;

use Closure;
use Config;
use Auth;
use Response;
use Exception;
use JWT;
use User;
use Input;
use Session;
use URL;

class Token
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
        $token = Input::get('x-auth-token', $request->header('x-auth-token', $_COOKIE['x-auth-token']));

        if ($token) {
            try {
                $payload = (array)JWT::decode($token, Config::get('app.key'));
                Auth::onceUsingId($payload['sub']);
                Auth::user()->auth_totp = 0;
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 401);
            }
        }
        return $next($request);
    }
}

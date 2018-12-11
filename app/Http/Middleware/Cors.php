<?php namespace App\Http\Middleware;

use Closure;

class Cors
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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, X-Auth-Token, X-Request-With');

        if ($request->method() == 'OPTIONS') {
            return response('OK');
        }
        return $next($request);
    }
}

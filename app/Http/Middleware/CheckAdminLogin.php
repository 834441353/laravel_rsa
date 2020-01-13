<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd(session('is_login'));
        if(!session('is_login')){
            return redirect()->to('login')->withErrors('请先登入');
        }
        return $next($request);
    }
}

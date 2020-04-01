<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

//        $data = [
//            'ip' => $request->getClientIp(),
//            'url' => $request->path(),
//            'method' => $request->getRealMethod(),
//            'content' => json_encode($request->all()),
//        ];
//        Log::info($data);
//        return $next($request);
        $response = $next($request);
        if ($request->path() == 'device' or $request->path() == 'imgrecevice') {
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'method' => $request->getRealMethod(),
                'request_content' => json_encode($request->all()),
                'response_content' => $response->content(),
                'response_statuscode' => $response->status(),
            ];
        } else {
            if (!session('is_login')) {
                $user = [];
            } else {
                $user = $request->session()->get('a_id');
//                dd($sessionData);
            }
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'method' => $request->getRealMethod(),
                'userid' => $user,
                'request_content' => json_encode($request->all()),
                'response_statuscode' => $response->status(),
            ];
        }
        if ($response->content() == '-2') {
            Log::warning($data);
        } else {
            Log::info($data);
        }

        return $response;
    }
}

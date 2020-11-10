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
        if ($request->path() == 'device/download') {
//            dd($response);
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'time' => $this->getElapsedTime(),
                'method' => $request->getRealMethod(),
                'request_content' => json_encode($request->all()),
//                'response_content' => $response->content(),
//                'response_statuscode' => $response->status(),
            ];
            Log::info($data);
            return $response;
        }elseif($request->path() == 'logs'){
            return $response;
        }

        if ($request->path() == 'device2') {
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'time' => $this->getElapsedTime(),
                'method' => $request->getRealMethod(),
                'request_content' => $request->request->has('info') ? $request->request->get('info') : $request->all(),
                'response_content' => base64_decode($response->content()) == -2 ? base64_decode($response->content()) : $response->content(),
                'response_statuscode' => $response->status(),
            ];
        }elseif($request->path() == 'device' or $request->path() == 'Stdevice'){
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'time' => $this->getElapsedTime(),
                'method' => $request->getRealMethod(),
                'request_content' => json_encode($request->all()),
                'response_content' => $response->content(),
                'response_statuscode' => $response->status(),
            ];
        } elseif ($request->path() == 'imgrecevice' or $request->path() == 'imgreceviceSt') {
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'time' => $this->getElapsedTime(),
                'method' => $request->getRealMethod(),
                'request_content' => json_encode($request->all()),
                'response_content' => $response->content(),
                'response_statuscode' => $response->status(),
            ];
        } elseif ($request->path() == 'deviceic') {
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'time' => $this->getElapsedTime(),
                'method' => $request->getRealMethod(),
                'request_content' => json_encode($request->all()),
                'response_content' => base64_decode($response->content()),
                'response_statuscode' => $response->status(),
            ];
        } else {
            if (!session('is_login')) {
                $user = $request->session()->all();
            } else {
                $user = $request->session()->get('a_id');
//                dd($sessionData);
            }
            $data = [
                'ip' => $request->getClientIp(),
                'url' => $request->path(),
                'time' => $this->getElapsedTime(),
                'method' => $request->getRealMethod(),
                'userid' => $user,
                'request_content' => json_encode($request->all()),
                'response_statuscode' => $response->status(),
            ];
        }
        if ($response->content() == '-2') {
            Log::warning(json_encode($data));
        } else {
            Log::info(json_encode($data));
        }
        //dd(time(),microtime(true),request(),response());
        return $response;
    }

    function getElapsedTime(int $decimals  = 4)
    {
        return number_format(microtime(true) - request()->server('REQUEST_TIME_FLOAT'), $decimals) . ' s';
    }
}

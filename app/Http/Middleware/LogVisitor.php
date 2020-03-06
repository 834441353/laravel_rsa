<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogVisitor
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

        $data = [
            'ip' => $request->getClientIp(),
            'url' => $request->path(),
            'method' => $request->getRealMethod(),
            'content' => json_encode($request->all()),
        ];
        Log::info($data);
        return $next($request);
    }
}

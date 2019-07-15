<?php
/**
 * User: jrient
 * Date: 2019/7/15
 * Time: 23:09
 */

namespace App\Http\Middleware;

use Closure;

class Debug
{
    public function handle($request, Closure $next)
    {
        $ipWhiteList = explode(',',env('DEBUG_IP', ''));
        if(in_array($request->getClientIp(), $ipWhiteList)) {
            config(['app.debug' => true]);
        }
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);

        $restrictedIps = ['127.0.0.1', '102.129.158.0'];
if(in_array($request->ip(), $restrictedIps)){
  App::abort(403, 'Request forbidden');
}
return $next($request);
    }
    
}

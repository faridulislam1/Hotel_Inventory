<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle( Request $request, Closure $next)
    {
        // if (!Auth::check() || Auth::user()->role !== $role) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        if(!$request->user()|| !$request->user()->isAdmin()){
            return response()->json([
                'message'=>'unauthorized'

            ],404);
        }

        return $next($request);
    }
}

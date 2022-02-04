<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoleApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role == 'staff' && $request->user()->role_id == 3) {
            dd('staff2');
            return $next($request);
        }

        if ($role == 'user' && $request->user()->role_id == 2 || $role == 'user' && $request->user()->role_id == 1) {
            return $next($request);
        }   

        if ($role == 'admin' && $request->user()->role_id == 1) {
            return $next($request);
        }

        return response()->json([
            'status' => 403,
            'message' => __('auth.unauthorized'),
        ], 403);
    }
}

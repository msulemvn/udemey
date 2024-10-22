<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        if ($role) {
            /** @var \App\Models\User|null $user */
            $user =  Auth::user();
            if (Auth::check() && $user->hasAnyRole($role)) {
                return $next($request);
            }
            return ApiResponse::success(message: 'Unauthorized');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\ApiResponse;
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
    public function handle($request, Closure $next, ...$roles)
    {
        if ($roles) {
            $user = Auth::user();
            /** @var \App\Models\User|null $user */
            if (Auth::check() && $user->hasAnyRole($roles)) {
                return $next($request);
            }
            return ApiResponse::success(message: 'Unauthorized');
        } else {
            return $next($request);
        }
    }
}

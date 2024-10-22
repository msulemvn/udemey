<?php

namespace App\Http\Middleware;

use App\GlobalVariables\PermissionVariable;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to public routes
        if (in_array($request->path(), PermissionVariable::publicRoutes())) {
            return $next($request);
        }

        $user = Auth::user();
        if ($user) {
            /** @var \App\Models\User|null $user */
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            $availablePermissions = PermissionVariable::allRoutes();
            foreach ($availablePermissions as $permission) {
                $path = array_key_exists('prefix', $permission) ? $permission['prefix'] . $permission['path'] : $permission['path'];
                if ($path == $request->path() && in_array($permission['permission'], $permissions)) {
                    return $next($request);
                }
            }
        }
        return ApiResponse::success(message: 'Unauthorized');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HasRoles, HasPermissions;
    public function login(LoginRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();
        $token = Auth::attempt($validated);
        try {
            $user = Auth::user();
            $roleName = $user->getRoleNames()[0];
            if ($roleName) {
                $data['role'] = $roleName;
                $role = Role::findByName($roleName);
                $permissions = $role->permissions()->pluck('name')->toArray();
                $data['permissions'] = $permissions;
            }
            $data['access_token'] = $token;
        } catch (\Throwable $th) {
            $errors = ['credentials' => ['Email or password is incorrect. Please try again.']];
            return ApiResponse::error(message: 'Invalid credentials', errors: $errors);
        }

        return ApiResponse::success(data: $data);
    }

    public function logout()
    {
        Auth::logout();
        return ApiResponse::success(message: 'Successfully logged out');
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return ApiResponse::success(data: ['access_token' => $token]);
    }
}

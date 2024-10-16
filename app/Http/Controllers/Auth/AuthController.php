<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Services\Auth\AuthService;
use App\Http\Requests\Auth\LoginAuthRequest;
use Spatie\Permission\Traits\HasPermissions;

class AuthController extends Controller
{
    use HasRoles, HasPermissions;
    protected $loginAuthService;

    public function __construct(AuthService $loginAuthService)
    {
        $this->loginAuthService = $loginAuthService;
    }

    public function login(LoginAuthRequest $request)
    {
        // Retrieve the validated input data...
        $token = Auth::attempt($request->validated());
        try {
            $user = Auth::user();
            /** @var \App\User|null $user */
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

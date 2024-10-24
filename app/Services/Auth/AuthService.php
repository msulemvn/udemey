<?php

namespace App\Services\Auth;

use App\DTOs\Auth\AuthDTO;
use App\Helpers\ApiResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Summary of login
     * @param mixed $request
     * @return mixed
     */
    public function login($request)
    {
        try {
            // Retrieve the validated input data...
            $token = Auth::attempt((new AuthDTO($request))->toArray());
            $user = Auth::user();
            /** @var \App\Models\User|null $user */
            $roleName = $user->getRoleNames()[0];
            if ($roleName) {
                $data['role'] = $roleName;
                $role = Role::findByName($roleName);
                $permissions = $role->permissions()->pluck('name')->toArray();
                $data['permissions'] = $permissions;
                $data['2fa'] =  ($user->google2fa_secret) ? true : false;
            }
            $data['access_token'] = $token;
            return ['data' => $data];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}

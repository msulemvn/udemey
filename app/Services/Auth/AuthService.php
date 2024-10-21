<?php

namespace App\Services\Auth;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Generate two-factor authentication
     *
     * @param GenerateTwoFactorloginData $loginData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login($loginData)
    {
        try {
            // Retrieve the validated input data...
            $token = Auth::attempt($loginData);
            $user = Auth::user();
            /** @var \App\User|null $user */
            $roleName = $user->getRoleNames()[0];
            if ($roleName) {
                $data['role'] = $roleName;
                $role = Role::findByName($roleName);
                $permissions = $role->permissions()->pluck('name')->toArray();
                $data['permissions'] = $permissions;
                $data['2fa'] =  ($user->google2fa_secret) ? true : false;
            }
            $data['access_token'] = $token;
            return ['success' => true, 'data' => $data];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Invalid credentials', 'errors' =>  ['credentials' => ['Email or password is incorrect. Please try again.']], 'loginData' => $loginData, 'exception' => $e];
        }
    }
}

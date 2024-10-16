<?php

namespace App\Services\Auth;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Auth\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    /**
     * Generate two-factor authentication
     *
     * @param GenerateTwoFactorloginData $loginData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login($loginData)
    {
        // Retrieve the validated input data...
        $token = Auth::attempt($loginData);
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
            return ['success' => false, 'message' => 'Invalid credentials', 'errors' =>  ['credentials' => ['Email or password is incorrect. Please try again.']], 'loginData' => $loginData, 'exception' => $th];
        }
        return ['success' => true, 'data' => $data];
    }
}

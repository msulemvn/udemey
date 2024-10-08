<?php

namespace App\Services\User;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use \Illuminate\Http\JsonResponse;


class UserService implements UserServiceInterface
{
    public function store(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function changePassword(array $data): JsonResponse
    {
        // Get the authenticated user from the JWT token
        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($data['current_password'], $user->password)) {
            return ApiResponse::error('Current password is incorrect', 401);
        }

        if (Hash::check($data['new_password'], $user->password)) {
            return ApiResponse::error('New password cannot be same as old', 401);
        }

        // Update the user's password
        $user->password = bcrypt($data['new_password']);
        $user->save();

        // Return a success response
        return ApiResponse::success(message: 'Password changed successfully');
    }
}

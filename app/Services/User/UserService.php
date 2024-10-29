<?php

namespace App\Services\User;

use App\Models\User;
use App\DTOs\User\UserDTO;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    public function store($request)
    {
        try {
            $userDTO = new UserDTO($request);
            $user = User::create($userDTO->toArray());

            return ['data' => $user->toArray()];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function changePassword($data)
    {
        // Get the authenticated user from the JWT token
        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($data['current_password'], $user->password)) {
            return ApiResponse::success(message: 'Current password is incorrect', statusCode: Response::HTTP_UNAUTHORIZED);
        }

        if (Hash::check($data['new_password'], $user->password)) {
            return ApiResponse::success(message: 'New password cannot be same as old', statusCode: Response::HTTP_UNAUTHORIZED);
        }

        // Update the user's password
        $user->password = $data['new_password'];
        /** @var \App\Models\User|null $user */
        $user->save();

        // Return a success response
        return ['message' => 'Password changed successfully'];
    }
    public function profile()
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        /** @var \App\Models\User|null $user */
        $myRole = $user->getRoleNames()[0];
        if ($myRole == 'admin') {
            $adminData = User::find($userId)->toArray();
            $adminData['2fa'] =  ($adminData['google2fa_secret']) ? true : false;
            unset($adminData['google2fa_secret']);
        }

        return ['data' => $adminData ?? User::with($myRole)->whereId($userId)->get()->mapWithKeys(function ($user) {
            $role = $user->getRoleNames()[0];
            return [
                'id' => $user->$role->id,
                'name' => $user->name,
                'email' => $user->email,
                '2fa' => $user->google2fa_secret ? true : false,
            ];
        })->toArray()];
    }
    public function profiles()
    {
        return ['data' => User::get()->filter(function ($user) {
            return $user->google2fa_secret !== null;
        })->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                '2fa' => $user->google2fa_secret ? true : false,
            ];
        })->toArray()];
    }
}

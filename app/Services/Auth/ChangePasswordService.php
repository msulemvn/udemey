<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService
{
    public function changePassword($request)
    {
        $user = auth()->user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            throw new \Exception('The current password is incorrect.');
        }

        // Update the user's password
        $user->password = $request->new_password;
        $user->remember_token = Str::random(60);
        /** @var \App\Models\User|null $user */
        $user->save();

        return ['message' => 'Password changed successfully.'];
    }
}

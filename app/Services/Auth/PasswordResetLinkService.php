<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\PasswordReset;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendPasswordResetLinkMail;
use Illuminate\Support\Facades\Password;


class PasswordResetLinkService
{
    public function passwordResetLink($request)
    {
        $user = User::where('email', $request->email)->first();
        try {
            $token = Str::random(60);
            PasswordReset::createToken($user->email, $token);
            $status = SendPasswordResetLinkMail::dispatch([
                'name' => $user->name,
                'email' => $user->email,
                'url' => 'http://localhost:8080/reset-password?email=' . $user->email . '&token=' . $token,
            ]);
            return $status ? ['message' => 'Reset link sent successfully'] : ['message' => 'Failed to send reset link'];
        } catch (\Exception $e) {
            ApiResponse::error(request: $request, exception: $e);
        }
    }
}

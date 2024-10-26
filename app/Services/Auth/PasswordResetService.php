<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Mail\PasswordResetMail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function resetPassword($request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'remember_token' => Str::random(60),
                ])->save();
                $user = User::where('email', $request->email)->first();
                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'url' => 'http://localhost:8080/reset-password?email=' . $user->email . '&token=' . $user->remember_token,
                ];
                try {
                    PasswordResetMail::dispatch($data);
                } catch (\Exception $e) {
                    ApiResponse::error(request: $request, exception: $e);
                }
            }
        );

        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ? ['message' => 'Reset link sent successfully'] : ['message' => 'Failed to send reset link'];
    }
}

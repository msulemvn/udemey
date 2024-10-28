<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Mail\PasswordResetMail;
use App\DTOs\Auth\PasswordResetDTO;
use App\Jobs\SendPasswordResetMail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\Auth\PasswordResetRequest;

class PasswordResetService
{
    public function resetPassword($request)
    {
        $status = Password::reset(
            (new PasswordResetDTO($request))->toArray(),
            function ($user) {
                $user->forceFill([
                    'remember_token' => Str::random(60),
                ])->save();
                SendPasswordResetMail::dispatch([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? ['message' => 'Password reset successfully']
            : ['message' => 'Failed to reset password'];
    }
}

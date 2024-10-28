<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\DTOs\Auth\PasswordResetDTO;
use App\Jobs\SendPasswordResetMail;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function resetPassword($request)
    {
        $status = Password::reset(
            (new PasswordResetDTO($request))->toArray(),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();
                SendPasswordResetMail::dispatch([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
            }
        );

        return ['message' => __($status)];
    }
}

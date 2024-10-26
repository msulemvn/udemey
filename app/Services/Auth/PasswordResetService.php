<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

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
                event(new PasswordReset(user: $user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
        return [
            'message' => $status
        ];
    }
}



// try {
//     $user = User::where('email', $request->email)->first();
//     $data = [
//         'name' => $user['name'],
//         'email' => $user['email'],
//         'message' => 'Hello, this is a custom mail!',
//     ];
//     SendPasswordResetMail::dispatch($data);

//     return ['message' => 'Mail sent successfully!'];
// } catch (\Exception $e) {
//     ApiResponse::error(request: $request, exception: $e);
// }



// // Dispatch the job
// SendTestMail::dispatch($data);

// return response()->json(['message' => 'Mail sent successfully!']);


// ResetPassword::createUrlUsing(function ($notifiable, string $token) {
//     return 'http://localhost:8080/reset-password?email=' . $notifiable->getEmailForPasswordReset() . '&token=' . $token;
// });

// $status = Password::sendResetLink($request->only('email'));
// if ($status === Password::RESET_LINK_SENT) {
//     return ApiResponse::success(message: 'Reset link sent successfully');
// } else {
//     return ApiResponse::success(message: 'Failed to send reset link');
// }
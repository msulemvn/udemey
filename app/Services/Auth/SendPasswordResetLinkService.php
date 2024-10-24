<?php

namespace App\Services\Auth;

use App\Helpers\ApiResponse;
use App\Jobs\SendForgotPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SendPasswordResetLinkService
{
    public function sendPasswordResetLink($request)
    {

        try {
            $user = User::where('email', $request->email)->first();
            $data = [
                'name' => $user['name'],
                'email' => $user['email'],
                'message' => 'Hello, this is a custom mail!',
            ];
            SendForgotPasswordMail::dispatch($data);

            return ['message' => 'Mail sent successfully!'];
        } catch (\Exception $e) {
            ApiResponse::error(request: $request, exception: $e);
        }



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
        return '';
    }
}

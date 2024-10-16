<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

class SendEmailVerificationNotificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $user = Auth::user();
        /** @var \App\User|null $user */
        if ($user->hasVerifiedEmail()) {

            return ApiResponse::success(message: 'Email already verified.');
        }

        /** @var \App\User|null $user */
        $user->sendEmailVerificationNotification();

        return ApiResponse::success(message: 'Email verification link sent successfully.');
    }
}

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
        if (Auth::user()->hasVerifiedEmail()) {

            return ApiResponse::message(message: 'Email already verified.');
        }

        Auth::user()->sendEmailVerificationNotification();

        return ApiResponse::message(message: 'Email verification link sent successfully.');
    }
}

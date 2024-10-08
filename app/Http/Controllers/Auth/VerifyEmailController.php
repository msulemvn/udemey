<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;


class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return ApiResponse::success(message: 'Email already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return ApiResponse::success(message: 'Email verified successfully.');
    }
}

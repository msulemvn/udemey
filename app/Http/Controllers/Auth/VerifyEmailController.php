<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\EmailVerificationAuthRequest;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Auth\Events\Verified;


class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */

    public function __invoke(EmailVerificationAuthRequest $request)
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

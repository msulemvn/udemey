<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\SendPasswordResetLinkAuthRequest;

class SendPasswordResetLinkController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(SendPasswordResetLinkAuthRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(message: 'Reset link sent successfully');
        } else {
            return ApiResponse::success(message: 'Failed to send reset link');
        }
    }
}

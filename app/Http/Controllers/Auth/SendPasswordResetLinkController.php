<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\SendPasswordResetLinkRequest;

class SendPasswordResetLinkController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SendPasswordResetLinkRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(message: 'Reset link sent successfully');
        } else {
            return ApiResponse::error(message: 'Failed to send reset link');
        }
    }
}

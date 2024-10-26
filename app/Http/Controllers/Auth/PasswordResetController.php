<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\ResetPasswordService;
use App\Http\Requests\Auth\ResetPasswordAuthRequest;

class PasswordResetController extends Controller
{
    protected $resetPasswordService;
    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(ResetPasswordAuthRequest $request)
    {
        $response = $this->resetPasswordService->resetPassword($request);
        return  ApiResponse::success(message: $response['message']);
    }
}

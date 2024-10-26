<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordResetService;
use App\Http\Requests\Auth\PasswordResetRequest;

class PasswordResetController extends Controller
{
    protected $resetPasswordService;
    public function __construct(PasswordResetService $passwordReset)
    {
        $this->resetPasswordService = $passwordReset;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(PasswordResetRequest $request)
    {
        $response = $this->resetPasswordService->resetPassword($request);
        return  ApiResponse::success(message: $response['message']);
    }
}

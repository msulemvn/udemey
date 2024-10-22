<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponse;
use App\Services\User\RegisterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUserRequest;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function register(RegisterUserRequest $request)
    {
        $user = $this->registerService->registerUser($request);
        return ApiResponse::success(message: 'You are successfully registered');
    }
}

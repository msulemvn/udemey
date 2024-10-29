<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\ChangePasswordUserRequest;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function profile()
    {
        $response = $this->userService->profile();
        return ApiResponse::success(data: $response['data'] ?? []);
    }
    public function profiles()
    {
        $response = $this->userService->profiles();
        return ApiResponse::success(data: $response['data'] ?? []);
    }

    public function changePassword(ChangePasswordUserRequest $request)
    {
        return $this->userService->changePassword($request);
    }
}

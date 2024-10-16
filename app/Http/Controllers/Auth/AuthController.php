<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Services\Auth\AuthService;
use App\Http\Requests\Auth\LoginAuthRequest;
use Spatie\Permission\Traits\HasPermissions;

class AuthController extends Controller
{
    use HasRoles, HasPermissions;
    protected $AuthService;

    public function __construct(AuthService $AuthService)
    {
        $this->AuthService = $AuthService;
    }

    public function login(LoginAuthRequest $request)
    {
        $response = $this->AuthService->login($request->validated());
        return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::error(message: $response['message'] ?? null, errors: $response['errors'], request: $response['request'] ?? null, exception: $response['exception'] ?? null, statusCode: $response['statusCode'] ?? null);
    }

    public function logout()
    {
        Auth::logout();
        return ApiResponse::success(message: 'Successfully logged out');
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return ApiResponse::success(data: ['access_token' => $token]);
    }
}

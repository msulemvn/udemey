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
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginAuthRequest $request)
    {
        $response = $this->authService->login($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
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

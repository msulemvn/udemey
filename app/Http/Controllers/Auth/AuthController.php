<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();
        $token = Auth::attempt($validated);
        return $token ? ApiResponse::success(data: ['access_token' => $token]) : ApiResponse::error('Invalid credentials', 401);
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

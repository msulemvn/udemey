<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Reset2FARequest;
use App\Services\Auth\TwoFactorAuthService;

class TwoFactorAuthController extends Controller
{
    private $twoFactor;

    public function __construct(TwoFactorAuthService $twoFactor)
    {
        $this->twoFactor = $twoFactor;
    }

    public function verify2FA(Request $request)
    {
        $response = $this->twoFactor->verify2FA($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Generate a secret key for Google 2-Factor Authentication.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSecretKey(Request $request)
    {
        $response = $this->twoFactor->generateSecretKey($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Enable Google 2-Factor Authentication for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable2FA(Request $request)
    {
        $response = $this->twoFactor->enable2FA($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Disable Google 2-Factor Authentication for the current user.
     *
     * @param mixed
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable2FA(Request $request)
    {
        $response = $this->twoFactor->disable2FA($request);;
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Disable Google 2-Factor Authentication for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset2FA(Reset2FARequest $request)
    {
        $response = $this->twoFactor->reset2FA($request);;
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorAuthService;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthController extends Controller
{
    private $twoFactorService;

    public function __construct(TwoFactorAuthService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function verify2FA(Request $request)
    {
        $response = $this->twoFactorService->verify2FA($request);
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
        $response = $this->twoFactorService->generateSecretKey($request);
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
        $response = $this->twoFactorService->enable2FA($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Disable Google 2-Factor Authentication for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable2FA(Request $request)
    {
        $response = $this->twoFactorService->disable2FA($request);;
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorService;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorController extends Controller
{
    private $TwoFactorService;

    public function __construct(TwoFactorService $TwoFactorService)
    {
        $this->TwoFactorService = $TwoFactorService;
    }

    public function verify2FA(Request $request)
    {
        $response = $this->TwoFactorService->verify2FA($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Generate a secret key for Google 2-Factor Authentication.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSecretKey(Request $request)
    {
        $response = $this->TwoFactorService->generateSecretKey($request);
        return ApiResponse::success(message: $response['message'], data: $response['data'] ?? [], errors: $response['errors'], statusCode: $response['statusCode'] ?? 200);
    }

    /**
     * Enable Google 2-Factor Authentication for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable2FA(Request $request)
    {
        $response = $this->TwoFactorService->enable2FA($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? []);
    }

    /**
     * Disable Google 2-Factor Authentication for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable2FA(Request $request)
    {
        $response = $this->TwoFactorService->disable2FA($request);;
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? null);
    }
}

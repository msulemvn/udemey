<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\VerifyTokenAuthRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class TokenVerificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(VerifyTokenAuthRequest $request): JsonResponse
    {
        $validatedData = $request->safe()->only(['email', 'token']);
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            return ApiResponse::success(statusCode: Response::HTTP_UNAUTHORIZED);
        }

        $broker = Password::broker();

        /** @var \App\Models\User|null $broker */
        if ($broker->tokenExists($user, $validatedData['token'])) {
            return ApiResponse::success(
                message: 'Token verified successfully',
            );
        } else {
            return ApiResponse::success(
                message: 'Invalid or expired token',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }
    }
}

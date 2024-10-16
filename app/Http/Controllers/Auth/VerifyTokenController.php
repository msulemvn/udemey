<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\VerifyTokenAuthRequest;
use Symfony\Component\HttpFoundation\Response;

class VerifyTokenController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(VerifyTokenAuthRequest $request)
    {
        $validatedData = $request->safe()->only(['email', 'token']);
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            return ApiResponse::error(
                message: 'Invalid or expired token.',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        $broker = Password::broker();

        /** @var \App\User|null $broker */
        if ($broker->tokenExists($user, $validatedData['token'])) {
            return ApiResponse::success(
                message: 'Token verified successfully',
            );
        } else {
            return ApiResponse::error(
                message: 'Invalid or expired token',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\VerifyTokenRequest;
use Symfony\Component\HttpFoundation\Response;

class VerifyTokenController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(VerifyTokenRequest $request)
    {

        $validatedData = $request->safe()->only(['email', 'token']);
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            return ApiResponse::error(
                error: 'Invalid or expired token.',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        $broker = Password::broker();

        if ($broker->tokenExists($user, $validatedData['token'])) {
            return ApiResponse::success(
                message: 'Token verified successfully',
            );
        } else {
            return ApiResponse::error(
                error: 'Invalid or expired token',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }
    }
}

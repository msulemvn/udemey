<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmailVerificationController extends Controller
{
    private $sendEmailVerificationService;

    public function __construct(EmailVerificationService $sendEmailVerificationService)
    {
        $this->sendEmailVerificationService = $sendEmailVerificationService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();
        /** @var \App\Models\User|null $user */
        if ($user->hasVerifiedEmail()) {

            return ApiResponse::success(message: 'Email already verified.');
        }

        /** @var \App\Models\User|null $user */
        $user->sendEmailVerification();

        return ApiResponse::success(message: 'Email verification link sent successfully.');
    }
}

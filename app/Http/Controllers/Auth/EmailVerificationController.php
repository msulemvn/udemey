<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Auth\EmailVerificationRequest;


class EmailVerificationController extends Controller
{
    private $emailVerification;

    public function __construct(EmailVerificationService $emailVerification)
    {
        $this->emailVerification = $emailVerification;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse
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

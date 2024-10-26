<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordResetLinkService;
use App\Http\Requests\Auth\PasswordResetLinkRequest;

class PasswordResetLinkController extends Controller
{
    protected $passwordResetLink;
    public function __construct(PasswordResetLinkService $passwordResetLink)
    {
        $this->passwordResetLink = $passwordResetLink;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(PasswordResetLinkRequest $request)
    {
        $response = $this->passwordResetLink->passwordResetLink($request);
        return  ApiResponse::success(message: $response['message']);
    }
}

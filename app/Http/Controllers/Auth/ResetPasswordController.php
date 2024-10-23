<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendTestMail;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\ResetPasswordAuthRequest;

class ResetPasswordController extends Controller
{
    protected $resetPasswordService;
    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(ResetPasswordAuthRequest $request)
    {
        $response = $this->resetPasswordService->resetPassword($request);
        return  ApiResponse::success(message: $response['message']);

        // $data = [
        //     'name' => 'John Doe',
        //     'email' => 'john.doe@example.com',
        //     'message' => 'Hello, this is a custom mail!',
        // ];

        // // Dispatch the job
        // SendTestMail::dispatch($data);

        // return response()->json(['message' => 'Mail sent successfully!']);


        // ResetPassword::createUrlUsing(function ($notifiable, string $token) {
        //     return 'http://localhost:8080/reset-password?email=' . $notifiable->getEmailForPasswordReset() . '&token=' . $token;
        // });
    }
}

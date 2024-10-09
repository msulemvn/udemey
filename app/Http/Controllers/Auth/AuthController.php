<?php

namespace App\Http\Controllers\Auth;

use PragmaRX\Google2FA\Google2FA;

use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();
        $token = Auth::attempt($validated);
        return $token ? ApiResponse::success(data: ['access_token' => $token]) : ApiResponse::error('Invalid credentials', statusCode: Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        Auth::logout();
        return ApiResponse::success(message: 'Successfully logged out');
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return ApiResponse::success(data: ['access_token' => $token]);
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->uses_two_factor_auth) {
            $google2fa = new Google2FA();

            if ($request->session()->has('2fa_passed')) {
                $request->session()->forget('2fa_passed');
            }

            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:auth:attempt', true);
            $request->session()->put('2fa:auth:remember', $request->has('remember'));

            $otp_secret = $user->google2fa_secret;
            $one_time_password = $google2fa->getCurrentOtp($otp_secret);

            return redirect()->route('2fa')->with('one_time_password', $one_time_password);
        }

        return redirect()->intended($this->redirectPath());
    }
}

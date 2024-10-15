<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TwoFactorController extends Controller
{

    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $user_id = $request->session()->get('2fa:user:id');
        $remember = $request->session()->get('2fa:auth:remember', false);
        $attempt = $request->session()->get('2fa:auth:attempt', false);

        if (!$user_id || !$attempt) {
            return redirect()->route('login');
        }

        $user = User::find($user_id);

        if (!$user || !$user->uses_two_factor_auth) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();
        $otp_secret = $user->google2fa_secret;

        if (!$google2fa->verifyKey($otp_secret, $request->one_time_password)) {
            throw ValidationException::withMessages([
                'one_time_password' => [__('The one time password is invalid.')],
            ]);
        }

        $guard = config('auth.defaults.guard');
        $credentials = [$user->getAuthIdentifierName() => $user->getAuthIdentifier(), 'password' => $user->getAuthPassword()];

        if ($remember) {
            $guard = config('auth.defaults.remember_me_guard', $guard);
        }

        if ($attempt) {
            $guard = config('auth.defaults.attempt_guard', $guard);
        }

        if (Auth::guard($guard)->attempt($credentials, $remember)) {
            $request->session()->remove('2fa:user:id');
            $request->session()->remove('2fa:auth:remember');
            $request->session()->remove('2fa:auth:attempt');

            return redirect()->intended('/');
        }

        return redirect()->route('login')->withErrors([
            'password' => __('The provided credentials are incorrect.'),
        ]);
    }

    /**
     * Generate a secret key for Google 2-Factor Authentication.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSecretKey(Request $request)
    {
        $user = Auth::user();
        // Check if 2FA already enabled
        if ($user->google2fa_secret) {
            return ApiResponse::success(message: '2-Factor Authentication is already enabled.');
        }

        try {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = $google2fa->generateSecretKey();
            $user = Auth::user();
            Cache::put('google2fa_secret_' . $user->id, $secretKey, 60);
            return ApiResponse::success(data: ['google2fa_secret' => $secretKey]);
        } catch (\Throwable $th) {
            return ApiResponse::error(errors: ['google2fa_secret' => ['Failed to generate secret key.']], request: $request, exception: $th);
        }
    }

    /**
     * Enable Google 2-Factor Authentication for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable2FA(Request $request)
    {
        $user = Auth::user();
        // Check if 2FA already enabled
        if ($user->google2fa_secret) {
            return ApiResponse::success(message: '2-Factor Authentication is already enabled.');
        }

        $secretKey = Cache::get('google2fa_secret_' . $user->id);

        if (!$secretKey || empty($secretKey)) {
            return ApiResponse::error(errors: ['google2fa_secret' => ['Invalid or expired secret key.']], statusCode: Response::HTTP_BAD_REQUEST);
        }

        $otp = $request->input('one_time_password'); // Get OTP from user input
        $google2fa = app('pragmarx.google2fa');
        if ($google2fa->verifyGoogle2FA($secretKey, $otp)) {
            // OTP is valid
            $user->google2fa_secret = $secretKey;
            $user->save();
            Cache::forget('google2fa_secret_' . $user->id);

            return ApiResponse::success(message: '2-Factor Authentication successfully enabled.');
        }

        // OTP is invalid
        return ApiResponse::error(errors: ['one_time_password' => ['Invalid OTP.']]);
    }
}

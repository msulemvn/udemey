<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthService
{
    /**
     * Generate two-factor authentication
     *
     * @param mixed $request
     * @return mixed
     */
    public function generateSecretKey($request)
    {
        $user = Auth::user();
        // Check if 2FA already enabled
        if ($user->google2fa_secret) {
            return ['message' => '2-Factor Authentication is already enabled.'];
        }

        try {
            $google2fa = new Google2FA();
            $secretKey = $google2fa->generateSecretKey(32);
            $user = Auth::user();
            $result = Cache::put('google2fa_secret_' . $user->id, $secretKey, 60);
            return ['message' => $result ? 'Secret key generated successfully.' : 'Failed to generate secret key.', 'data' => ['type' => 'Time based (TOTP)', 'google2fa_secret' => $secretKey, 'label' => 'Udemey']];
        } catch (\Exception $e) {
            ApiResponse::error(request: $request, exception: $e);
        }
    }

    /**
     * Enable Google 2-Factor Authentication for the current user.
     *
     * @param $request
     * @return mixed
     */
    public function enable2FA($request)
    {
        $user = Auth::user();
        // Check if 2FA already enabled
        if ($user->google2fa_secret) {
            return ['message' => '2-Factor Authentication is already enabled.'];
        }

        $secretKey = Cache::get('google2fa_secret_' . $user->id);

        if (!$secretKey || empty($secretKey)) {
            return ['errors' => ['google2fa_secret' => ['Invalid or expired secret key.']], 'statusCode' => Response::HTTP_BAD_REQUEST];
        }



        $otp = $request->input('one_time_password'); // Get OTP from user input

        $google2fa = new Google2FA();
        /** @var \PragmaRX\Google2FA\Google2FA|null $google2fa */
        if ($google2fa->verifyKey($secretKey, $otp)) {           // OTP is valid
            $user->google2fa_secret = $secretKey;
            /** @var \App\Models\User|null $user */
            $user->save();
            Cache::forget('google2fa_secret_' . $user->id);
            return ['message' => '2-Factor Authentication successfully enabled.'];
        }

        // OTP is invalid
        return ['errors' => ['one_time_password' => ['Invalid OTP.']]];
    }

    /**
     * Disable Google 2-Factor Authentication for the current user.
     *
     * @param mixed
     * @return mixed
     */
    public function disable2FA($request)
    {
        $user = Auth::user();
        // Check if 2FA already enabled
        if (!$user->google2fa_secret) {
            return ['message' => '2-Factor Authentication is already disabled.'];
        }

        $password = $request->input('password');
        // Verify password
        if (Hash::check($password, $user->password)) {
            try {
                // OTP is valid
                $user->google2fa_secret = null;
                /** @var \App\Models\User|null $user */
                $result = $user->save();
                return ['message' => $result ? '2-Factor Authentication successfully disabled.' : 'Failed to disable 2-Factor Authentication'];
            } catch (\Exception $e) {
                ApiResponse::error(request: $request, exception: $e);
            }
        } else {
            return ['errors' => ['password' => ['Password is invalid']]];
        }
    }

    /**
     * Disable Google 2-Factor Authentication for the current user.
     *
     * @param mixed $request
     * @return mixed
     */
    public function reset2FA($request)
    {
        $user = User::find($request->userId);
        // Check if 2FA already enabled
        if (!$user->google2fa_secret) {
            return ['message' => '2-Factor Authentication is already disabled.'];
        }

        try {
            // OTP is valid
            $user->google2fa_secret = null;
            /** @var \App\Models\User|null $user */
            $result = $user->save();
            return ['message' => $result ? '2-Factor Authentication successfully reset.' : 'Failed to reset 2-Factor Authentication'];
        } catch (\Exception $e) {
            ApiResponse::error(request: $request, exception: $e);
        }
    }

    /**
     * Verify two-factor authentication
     *
     * @param mixed $request
     * @return mixed
     */
    public function verify2FA($request)
    {
        $user = Auth::user();
        if (!$user->google2fa_secret) {
            return ['message' => '2-Factor Authentication not enabled.'];
        }

        $google2fa = new Google2FA();
        $otp_secret = $user->google2fa_secret;

        if (!$google2fa->verifyKey($otp_secret, $request->one_time_password)) {
            return ['errors' => ['one_time_password' => ['The one time password is invalid.']]];
        }

        return ['message' => 'one_time_password verified successfully'];
    }
}

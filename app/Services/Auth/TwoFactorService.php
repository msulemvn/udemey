<?php

namespace App\Services\Auth;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TwoFactorService
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
     * @param Request $request
     * @return mixed
     */
    public function enable2FA($data)
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



        $otp = $data->input('one_time_password'); // Get OTP from user input

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
     * @param Request $request
     * @return mixed
     */
    public function disable2FA($data)
    {
        $user = Auth::user();
        // Check if 2FA already enabled
        if (!$user->google2fa_secret) {
            return ['message' => '2-Factor Authentication is already disabled.'];
        }

        $password = $data->input('password');
        // Verify password
        if (Hash::check($password, $user->password)) {
            try {
                // OTP is valid
                $user->google2fa_secret = null;
                /** @var \App\Models\User|null $user */
                $result = $user->save();
                return ['message' => $result ? '2-Factor Authentication successfully disabled.' : 'Failed to disable 2-Factor Authentication'];
            } catch (\Exception $e) {
                ApiResponse::error(request: $data, exception: $e);
            }
        } else {
            return ['errors' => ['password' => ['Password is invalid']]];
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

    /**
     * Check if the request is valid
     *
     * @param int $userId
     * @param bool $attempt
     * @return bool
     */
    protected function isValidRequest($userId, $attempt)
    {
        return $userId && $attempt;
    }

    /**
     * Get the user instance
     *
     * @param int $userId
     * @return User|null
     */
    protected function getUser($userId)
    {
        return User::find($userId);
    }

    /**
     * Check if the user is valid
     *
     * @param User $user
     * @return bool
     */
    protected function isValidUser($user)
    {
        return $user && $user->uses_two_factor_auth;
    }

    /**
     * Verify one-time password
     *
     * @param User $user
     * @param string $otp
     * @return bool
     */
    protected function verifyOtp($user, $otp)
    {
        $google2fa = new Google2FA();
        return $google2fa->verifyKey($user->google2fa_secret, $otp);
    }

    /**
     * Get the authentication guard
     *
     * @param bool $remember
     * @param bool $attempt
     * @return string
     */
    protected function getGuard($remember, $attempt)
    {
        $guard = config('auth.defaults.guard');
        if ($remember) {
            $guard = config('auth.defaults.remember_me_guard', $guard);
        }
        if ($attempt) {
            $guard = config('auth.defaults.attempt_guard', $guard);
        }
        return $guard;
    }

    /**
     * Get the authentication credentials
     *
     * @param User $user
     * @return array
     */
    protected function getCredentials($user)
    {
        return [$user->getAuthIdentifierName() => $user->getAuthIdentifier(), 'password' => $user->getAuthPassword()];
    }

    /**
     * Authenticate the user
     *
     * @param string $guard
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    protected function authenticate($guard, $credentials, $remember)
    {
        return Auth::guard($guard)->attempt($credentials, $remember);
    }

    /**
     * Clear session data
     *
     * @param mixed $request
     */
    protected function clearSession($request)
    {
        $request->session()->remove('2fa:user:id');
        $request->session()->remove('2fa:auth:remember');
        $request->session()->remove('2fa:auth:attempt');
    }
}

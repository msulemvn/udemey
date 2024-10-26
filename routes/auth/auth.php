<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\TwoFactorAuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/refresh', [AuthController::class, 'refresh'])
    ->name('refresh');

Route::post('/forgot-password', PasswordResetController::class)
    ->name('password.email');

Route::post('/reset-password', PasswordResetController::class)
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    Route::post('/email/verification-notification', EmailVerificationController::class)
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::controller(TwoFactorAuthController::class)->group(function () {
        Route::get('/generate-secret-key', 'generateSecretKey');
        Route::post('/enable-2fa', 'enable2FA');
        Route::post('/disable-2fa', 'disable2FA');
        Route::post('/verify-2fa',  'verify2FA')->name('2fa.verify');
    });
});

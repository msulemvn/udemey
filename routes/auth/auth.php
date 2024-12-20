<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\TwoFactorAuthController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

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

Route::post('/forgot-password', PasswordResetLinkController::class)
    ->name('password.email');

Route::post('/reset-password', PasswordResetController::class)
    ->name('password.update');

Route::post('/change-password', ChangePasswordController::class)
    ->name('password.change');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::controller(TwoFactorAuthController::class)->group(function () {
        Route::get('/generate-secret-key', 'generateSecretKey');
        Route::post('/enable-2fa', 'enable2FA');
        Route::post('/disable-2fa', 'disable2FA');
    });
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::controller(TwoFactorAuthController::class)->group(function () {
        Route::post('/reset-2fa/{userId}', 'reset2FA');
    });
});

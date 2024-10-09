<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SendPasswordResetLinkController;
use App\Http\Controllers\Auth\SendEmailVerificationNotificationController;
use App\Http\Controllers\Auth\TwoFactorController;

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

Route::post('/forgot-password', SendPasswordResetLinkController::class)
    ->name('password.email');

Route::post('/reset-password', ResetPasswordController::class)
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    Route::post('/email/verification-notification', SendEmailVerificationNotificationController::class)
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/profiles', 'profiles');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteSetting\SiteSettingController;

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::controller(SiteSettingController::class)->group(function () {
        Route::post('/create-site-setting', 'store');
        Route::post('/update-site-setting/{id}', 'update');
        Route::delete('/delete-site-setting/{id}', 'destroy');
        Route::post('/restore-site-setting/{id}', 'restore');
        Route::get('/get-site-settings', 'index');
    });
});

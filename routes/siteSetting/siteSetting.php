<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteSetting\SiteSettingController;

Route::middleware('auth:api')->group(function () {
    Route::controller(SiteSettingController::class)->group(function () {
        Route::post('/create-site-setting', 'createSetting');
        Route::post('/update-site-setting/{id}', 'updateSetting');
        Route::delete('/delete-site-setting/{id}', 'deleteSetting');
        Route::post('/restore-site-setting/{id}', 'restoreSoftDeletedSetting');
        Route::get('/get-site-settings', 'getSettings');
    });
});

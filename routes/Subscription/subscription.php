<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Subscription\SubscriptionController;

Route::middleware('auth:api')->group(function () {
    Route::controller(SubscriptionController::class)->group(function () {
        Route::get('/active-subscriptions', 'getAllActiveSubscriptions');
        Route::post('/subscribe', 'subscribe');
        Route::get('/check-my-subscription', 'checkMySubscription');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Subscription\SubscriptionController;

Route::middleware('auth:api')->controller(SubscriptionController::class)->group(function () {
    Route::post('subscribe', 'subscribe');
    Route::get('check-my-subscription', 'checkMySubscription');

    Route::middleware('role:admin')->get('active-subscriptions', 'getAllActiveSubscriptions');
});

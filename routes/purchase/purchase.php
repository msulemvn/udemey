<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchase\PurchaseController;

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index']);
});
Route::middleware('auth:api')->group(function () {
    Route::post('/purchase', [PurchaseController::class, 'checkout']);
});

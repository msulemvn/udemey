<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchase\PurchaseController;

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index']);
});

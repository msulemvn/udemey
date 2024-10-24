<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Page\PageController;

Route::controller(PageController::class)->group(function () {
    Route::get('/get-all-pages', 'index');
    Route::get('/get-page-by-id/{id}', 'index');
    Route::get('/get-page-by-slug/{slug}', 'index');
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::controller(PageController::class)->group(function () {
        Route::post('/create-page', 'store');
        Route::post('/update-page/{id}', 'update');
        Route::delete('/delete-page/{id}', 'destroy');
        Route::post('/restore-page/{id}', 'restore');
    });
});

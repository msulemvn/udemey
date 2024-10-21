<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Page\PageController;

Route::controller(PageController::class)->group(function () {
    Route::get('/get-all-pages', 'getPages');
    Route::get('/get-page-by-id/{pageId}', 'getPageById');
    Route::get('/get-page-by-slug/{slug}', 'getPageBySlug');
});

Route::middleware('auth:api')->group(function () {
    Route::controller(PageController::class)->group(function () {
        Route::post('/create-page', 'create');
        Route::post('/update-page/{pageId}', 'update');
        Route::delete('/delete-page/{pageId}', 'destroy');
        Route::post('/restore-page/{pageId}', 'restore');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Article\ArticleController;

Route::middleware('auth:api')->controller(ArticleController::class)->group(function () {

    Route::middleware('role:student')->group(function () {
        Route::get('article/{id}', 'show');
        Route::get('/articles/slug/{slug}',  'showBySlug');
    });
    Route::middleware('role:admin')->group(function () {
        Route::post('/create-article',  'store');
        Route::put('/update-article/{id}',  'update');
        Route::delete('/delete-article/{id}', 'destroy');
        Route::get('/articles',  'index');
    });
});

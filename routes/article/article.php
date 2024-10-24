<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Article\ArticleController;

Route::middleware('auth:api')->group(function () {
    Route::controller(ArticleController::class)->group(function () {
        Route::get('article/{id}', 'show');
        Route::get('/articles/{slug}',  'showBySlug');
        Route::post('/create-article',  'store');
        Route::get('/articles',  'index');
        Route::put('/update-article/{id}',  'update');
        Route::delete('/delete-article/{id}', 'destroy');
    });
});

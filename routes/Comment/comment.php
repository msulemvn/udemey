<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Comment\CommentController;

Route::middleware('auth:api')->group(function () {
    Route::controller(CommentController::class)->group(function () {
        Route::get('/{commentableType}/comments/{commentableId?}', 'index'); //article id
        Route::post('/{commentableType}/comments/{parentCommentId?}', 'store'); //optional parent id
        Route::delete('/{commentableType}/comments/{commentId}', 'destroy');
        Route::put('/{commentableType}/comments/{commentId}', 'update');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Comment\CommentController;

Route::middleware('auth:api')->group(function () {
    Route::controller(CommentController::class)->group(function () {
        Route::get('/{commentableType}/{slug}/comments/{commentId?}', 'index'); //article id
        Route::post('/{commentableType}/{slug}/post-comment/{parentCommentId?}', 'store'); //optional parent id
        Route::put('/{commentableType}/{slug}/update-comment/{commentId}', 'update');
        Route::middleware('role:student')->delete('/{commentableType}/{slug}/delete-comment/{commentId}', 'destroy')->middleware('role:student');
    });
});

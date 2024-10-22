<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\CategoryController;

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::get('/categories/{id}', 'show');
    Route::get('/categories/{id}/course-categories', 'getCategoryCourseCategories');
});
    
Route::middleware(['auth:api','role:admin'])->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::post('create-category', 'store');
        Route::put('/update-category/{id}', 'update');
        Route::delete('/delete-category/{id}', 'destroy');
    });
});

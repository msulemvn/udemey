<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseController;

Route::controller(CourseController::class)->group(function () {
    Route::get('/courses', 'index');
    Route::get('/courses/{slug}', 'getCourseBySlug');
    Route::get('/courses/{slug}/articles', 'getArticlewithCourse');
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::controller(CourseController::class)->group(function () {
        Route::post('/create-course', 'store');
        Route::put('/update-course/{id}', 'update');
        Route::delete('/delete-course/{id}', 'destroy');
    });
});

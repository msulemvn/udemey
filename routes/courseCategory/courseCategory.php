<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseCategory\CourseCategoryController;

Route::controller(CourseCategoryController::class)->group(function () {
    Route::get('/course-categories', 'index');
    Route::get('/course-categories/{id}', 'show');
    Route::get('/course-categories/{id}/course', 'getCoursewithCourseCategories');
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::controller(CourseCategoryController::class)->group(function () {
        Route::post('/create-course-categories', 'store');
        Route::put('/update-course-categories/{id}', 'update');
        Route::delete('/course-categories/{id}', 'destroy');
    });
});

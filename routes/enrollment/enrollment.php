<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Enrollment\EnrollmentController;

Route::middleware(['auth:api', 'role:student'])->group(function () {
    Route::controller(EnrollmentController::class)->group(function () {
        Route::get('/enrollments', 'index');
        Route::get('/enrollments/{slug}', 'getCourseBySlug');
    });
});

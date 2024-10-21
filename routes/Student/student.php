<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentController;

Route::middleware('auth:api')->group(function () {
    Route::controller(StudentController::class)->group(function () {
        Route::post('/delete-student/{student}', 'destroy');
        Route::get('/students', 'show');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Student\StudentController;
// included auth.php
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('profile', 'showUser');
    });

    Route::controller(UserController::class)->middleware('role:admin')->group(function () {
        Route::get('users', 'show');
    });

    Route::controller(StudentController::class)->middleware('role:admin,manager')->group(function () {
        Route::post('delete-student/{student}', 'destroy');
        Route::get('students', 'show');
    });

    Route::controller(ManagerController::class)->middleware('role:admin')->group(function () {
        Route::post('add-manager', 'store');
        Route::post('delete-manager/{manager}', 'destroy');
        Route::get('managers', 'show');
    });
});

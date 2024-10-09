<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Article\ArticleController;
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::controller(CourseController::class)->group(function () {
    Route::get('/courses', 'index');           // List all courses
    Route::get('/courses/{id}', 'show');       // Get specific course details
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'showUser');
    });

    Route::controller(UserController::class)->middleware('role:admin')->group(function () {
        Route::get('/users', 'show');
    });

    Route::controller(StudentController::class)->middleware('role:admin,manager')->group(function () {
        Route::post('/delete-student/{student}', 'destroy');
        Route::get('/students', 'show');
    });

    Route::controller(ManagerController::class)->middleware('role:admin')->group(function () {
        Route::post('/add-manager', 'store');
        Route::post('/delete-manager/{manager}', 'destroy');
        Route::get('/managers', 'show');
    });

    Route::middleware('role:admin')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::post('/create-course', 'store');
            Route::put('/update-course/{id}', 'update');
            Route::delete('/delete-course/{id}', 'destroy');
        });

        Route::controller(ArticleController::class)->group(function () {
            Route::post('/create-article', 'store');
            Route::get('/articles', 'index');
            Route::delete('/delete-article/{id}', 'destroy');
        });
    });

    Route::controller(ArticleController::class)->group(function () {
        Route::get('/articles/{id}', 'show');
    });
});

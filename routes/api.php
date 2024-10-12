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
    Route::get('/courses/{id}', 'show');       
    Route::get('/courses/{id}/articles', 'getArticlewithCourse');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes: auth
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'showUser');
    });
    Route::controller(ArticleController::class)->group(function () {
        Route::post('/create-article', 'store');
        Route::get('/articles', 'index');
        Route::delete('/delete-article/{id}', 'destroy');
        Route::get('/articles/{id}', 'show');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes: student
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:student')->group(function () {});

    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes: manager
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:manager')->group(function () {});

    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes: admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::post('/create-course', 'store');
            Route::put('/update-course/{id}', 'update');
            Route::delete('/delete-course/{id}', 'destroy');
        });
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'show');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes: admin, manager
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,manager')->group(function () {
        Route::controller(StudentController::class)->group(function () {
            Route::post('/delete-student/{student}', 'destroy');
            Route::get('/students', 'show');
        });

        Route::controller(ManagerController::class)->group(function () {
            Route::post('/add-manager', 'store');
            Route::post('/delete-manager/{manager}', 'destroy');
            Route::get('/managers', 'show');
        });
    });
});

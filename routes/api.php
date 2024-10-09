<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Student\StudentController;

use App\Http\Controllers\PageController;
use App\Http\Controllers\MenuItemController;
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


    Route::middleware( 'role:admin')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::get('courses/show/all', 'index');           // List all courses
            Route::get('courses/show/{id}', 'show');       // Get specific course details
            Route::post('courses/create', 'store');          // Create a new course (no need for 'create' in URL)
            Route::put('courses/{id}', 'update');     // Update a course
            Route::delete('courses/{id}', 'destroy'); // Soft delete a course
            Route::post('courses/{id}/restore', 'restore'); // Restore a soft-deleted course
        });
    });
});


// Dynamic Page Management Routes
Route::post('create-page', [PageController::class, 'create']);
Route::put('update-page/{page}', [PageController::class, 'updatePage']);
Route::delete('delete-page/{pageId}' , [PageController::class, 'deletePage']);
Route::get('get-page-by-slug/{slug}', [PageController::class, 'getPageBySlug']);

// Dynamic Menu Management Routes
Route::post('menu-items', [MenuItemController::class, 'create']);
Route::put('menu-items/{menuItem}', [MenuItemController::class, 'update']);
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Enrollment\EnrollmentController;
use App\Http\Controllers\SiteSetting\SiteSettingController;
use App\Http\Controllers\CourseCategory\CourseCategoryController;
use App\Http\Controllers\Subscription\SubscriptionController;


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
    Route::get('/courses/{slug}', 'show');
    Route::get('/courses/{id}/articles', 'getArticlewithCourse');
});
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::get('/categories/{id}', 'show');
    Route::get('/categories/{id}/course-categories', 'getCategoryCourseCategories');
});
Route::controller(CourseCategoryController::class)->group(function () {
    Route::get('/course-categories', 'index');
    Route::get('/course-categories/{id}', 'show');
    Route::get('/course-categories/{id}/course', 'getCoursewithCourseCategories');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/get-all-pages', 'getPages');
    Route::get('/get-page-by-id/{pageId}', 'getPageById');
    Route::get('/get-page-by-slug/{slug}', 'getPageBySlug');
});

Route::middleware('auth:api')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes: auth
    |--------------------------------------------------------------------------
    */
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile');
    });

    Route::middleware('role:admin')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Authenticated Routes: admin
        |--------------------------------------------------------------------------
        */
        Route::controller(CourseController::class)->group(function () {
            Route::post('/create-course', 'store');
            Route::put('/update-course/{id}', 'update');
            Route::delete('/delete-course/{id}', 'destroy');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::post('create-category', 'store');
            Route::put('/update-category/{id}', 'update');
            Route::delete('/delete-category/{id}', 'destroy');
        });

        Route::controller(CourseCategoryController::class)->group(function () {
            Route::post('/create-course-categories', 'store');
            Route::put('/update-course-categories/{id}', 'update');
            Route::delete('/course-categories/{id}', 'destroy');
        });

        Route::controller(ArticleController::class)->group(function () {
            Route::get('article/{id}', 'show');
            Route::get('/articles/slug/{slug}',  'showBySlug');
            Route::post('/create-article',  'store');
            Route::get('/articles',  'index');
            Route::put('/update-article/{id}',  'update');
            Route::delete('/delete-article/{id}', 'destroy');
        });

        Route::controller(SubscriptionController::class)->group(function () {
            Route::post('/subscribe', 'subscribe');
            Route::get('/check-my-subscription', 'checkMySubscription');
            Route::get('/active-subscriptions', 'getAllActiveSubscriptions');
        });

        Route::controller(StudentController::class)->group(function () {
            Route::post('/delete-student/{student}', 'destroy');
            Route::get('/students', 'show');
        });

        Route::get('/purchases', [PurchaseController::class, 'index']);

        Route::controller(PageController::class)->group(function () {
            Route::post('/create-page', 'create');
            Route::post('/update-page/{id}', 'update');
            Route::delete('/delete-page/{pageId}', 'destroy');
            Route::post('/restore-page/{pageId}', 'restore');
        });

        Route::controller(SiteSettingController::class)->group(function () {
            Route::post('/create-site-setting', 'createSetting');
            Route::post('/update-site-setting/{id}', 'updateSetting');
            Route::delete('/delete-site-setting/{id}', 'deleteSetting');
            Route::post('/restore-site-setting/{id}', 'restoreSoftDeletedSetting');
            Route::get('/get-site-settings/{id}', 'getSettings');
        });
    });

    Route::middleware('role:student')->group(function () {

        Route::post('/purchase', [PurchaseController::class, 'checkout']);

        Route::controller(EnrollmentController::class)->group(function () {
            Route::get('/enrollments', 'index');
            Route::get('/enrollments/{courseId}', 'show');
        });
    });
});

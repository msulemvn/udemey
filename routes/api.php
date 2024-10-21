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

require __DIR__ . '/auth/auth.php';
require __DIR__ . '/course/course.php';
require __DIR__ . '/category/category.php';
require __DIR__ . '/courseCategory/courseCategory.php';
require __DIR__ . '/page/page.php';
require __DIR__ . '/user/user.php';
require __DIR__ . '/article/article.php';
require __DIR__ . '/subscription/subscription.php';
require __DIR__ . '/student/student.php';
require __DIR__ . '/purchase/purchase.php';
require __DIR__ . '/siteSetting/siteSetting.php';
require __DIR__ . '/comment/comment.php';
require __DIR__ . '/enrollment/enrollment.php';

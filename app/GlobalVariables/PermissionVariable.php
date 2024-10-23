<?php

namespace App\GlobalVariables;

class PermissionVariable
{
    public const AUTH_PREFIX = 'auth';
    public static array $publicRoutes = [
        'api/login',
        'api/register',
        'api/forgot-password',
        'api/verify-token',
        'api/categories',
        'api/categories/*',
        'api/course-categories',
        'api/course-categories/*',
        'api/courses',
        'api/courses/*',
        'api/get-page-by-slug/*',
        'get-site-settings',
    ];

    public static array $viewProfile = [
        'path' => 'api/profile',
        'permission' => 'User can view profile',
        'roles' => ['admin', 'student']
    ];

    public static array $canRefresh = [
        'path' => 'api/refresh',
        'permission' => 'User can refresh',
        'roles' => ['admin', 'student']
    ];

    public static array $canLogout = [
        'path' => 'api/logout',
        'permission' => 'User can refresh',
        'roles' => ['admin', 'student']
    ];

    public static array $changePassword = [
        'path' => 'api/change-password',
        'permission' => 'User can change password',
        'roles' => ['admin', 'student']
    ];

    public static array $resetPassword = [
        'path' => 'api/reset-password',
        'permission' => 'User can reset password',
        'roles' => ['admin', 'student']
    ];

    public static array $generateSecret = [
        'path' => 'api/generate-secret-key',
        'permission' => 'User can reset password',
        'roles' => ['admin', 'student']
    ];
    public static array $enable2FA = [
        'path' => 'api/enable-2fa',
        'permission' => 'User can reset password',
        'roles' => ['admin', 'student']
    ];
    public static array $disable2FA = [
        'path' => 'api/disable-2fa',
        'permission' => 'User can disable 2FA',
        'roles' => ['admin', 'student']
    ];
    public static array $verify2FA = [
        'path' => 'api/verify-2fa',
        'permission' => 'User can verify 2FA',
        'roles' => ['admin', 'student']
    ];

    public static array $getComments = [
        'path' => 'comments',
        'permission' => 'User can see comments',
        'roles' => ['admin', 'student'],
        'prefix' => 'api/*/*',
    ];

    public static array $getCommentsSlug = [
        'path' => 'comments',
        'permission' => 'User can see comments',
        'roles' => ['admin', 'student'],
        'prefix' => 'api/*/*',
        'postfix' => '*',
    ];
    public static array $postComment = [
        'path' => 'post-comment',
        'permission' => 'User can post comment',
        'roles' => ['student'],
        'prefix' => 'api/*/*',
        'postfix' => '*'
    ];

    public static array $updateComment = [
        'path' => 'update-comment',
        'permission' => 'User can update comment',
        'roles' => ['admin', 'student'],
        'prefix' => 'api/*/*',
    ];

    public static array $deleteComment = [
        'path' => 'delete-comment',
        'permission' => 'User can delete comment',
        'roles' => ['student'],
        'prefix' => 'api/*/*',
        'postfix' => '*',
    ];

    public static array $createCategory = [
        'path' => 'create-category',
        'permission' => 'User can create category',
        'roles' => ['admin'],
    ];

    public static array $updateCategory = [
        'path' => 'update-category',
        'permission' => 'User can update category',
        'roles' => ['admin'],
    ];

    public static array $deleteCategory = [
        'path' => 'delete-category',
        'permission' => 'User can delete category',
        'roles' => ['admin'],
    ];

    public static array $createCourseCategory = [
        'path' => 'create-course-category',
        'permission' => 'User can create course category',
        'roles' => ['admin'],
    ];

    public static array $updateCourseCategory = [
        'path' => 'update-course-category',
        'permission' => 'User can update course category',
        'roles' => ['admin'],
        'postfix' => '*'
    ];

    public static array $deleteCourseCategory = [
        'path' => 'delete-course-category',
        'permission' => 'User can delete course category',
        'roles' => ['admin'],
        'postfix' => '*'
    ];


    public static array $createCourse = [
        'path' => 'create-course',
        'permission' => 'User can create course',
        'roles' => ['admin'],
    ];

    public static array $updateCourse = [
        'path' => 'update-course',
        'permission' => 'User can update course',
        'roles' => ['admin'],
        'postfix' => '*'
    ];
    public static array $deleteCourse = [
        'path' => 'delete-course',
        'permission' => 'User can delete course',
        'roles' => ['admin'],
        'postfix' => '*'
    ];

    public static array $canPurchase = [
        'path' => 'purchase',
        'permission' => 'User can purchase course',
        'roles' => ['student'],
    ];

    public static array $getPurchases = [
        'path' => 'purchases',
        'permission' => 'User can see purchases',
        'roles' => ['admin'],
    ];

    public static array $getEnrollments = [
        'path' => 'enrollments',
        'permission' => 'User can see course enrollments',
        'roles' => ['student'],
    ];

    public static array $getEnrollmentsSlug = [
        'path' => 'api/enrollments',
        'permission' => 'User can see course enrollments',
        'roles' => ['student'],
        'postfix' => '*'
    ];

    public static array $getArticles = [
        'path' => 'articles',
        'permission' => 'User can see articles',
        'roles' => ['admin'],
    ];

    public static array $getArticle = [
        'path' => 'articles',
        'permission' => 'User can see article',
        'roles' => ['student'],
        'postfix' => '*'
    ];

    public static array $createArticle = [
        'path' => 'create-article',
        'permission' => 'User can create article',
        'roles' => ['admin'],
    ];

    public static array $deleteArticle = [
        'path' => 'delete-article',
        'permission' => 'User can delete article',
        'roles' => ['admin'],
        'postfix' => '*'
    ];

    public static array $updateArticle = [
        'path' => 'update-article',
        'permission' => 'User can update article',
        'roles' => ['admin'],
        'postfix' => '*'
    ];
    public static array $canSubscribe = [
        'path' => 'subscribe',
        'permission' => 'User can subscribe',
        'roles' => ['student'],
    ];
    public static array $checkSubscription = [
        'path' => 'check-my-subscription',
        'permission' => 'User can see subscription',
        'roles' => ['student'],
    ];
    public static array $activeSubscriptions = [
        'path' => 'active-subscriptions',
        'permission' => 'User can see active subscriptions',
        'roles' => ['admin'],
    ];

    public static array $createPage = [
        'path' => 'create-page',
        'permission' => 'User can create page',
        'roles' => ['admin'],
    ];

    public static array $updatePage = [
        'path' => 'update-page',
        'permission' => 'User can update page',
        'roles' => ['admin'],
        'postfix' => '*',
    ];

    public static array $deletePage = [
        'path' => 'delete-page',
        'permission' => 'User can delete page',
        'roles' => ['admin'],
        'postfix' => '*',
    ];

    public static array $restorePage = [
        'path' => 'restore-page',
        'permission' => 'User can restore page',
        'roles' => ['admin'],
        'postfix' => '*',
    ];

    public static array $getPages = [
        'path' => 'get-all-pages',
        'permission' => 'User can get pages',
        'roles' => ['admin'],
    ];

    public static array $getPage = [
        'path' => 'get-page-by-id',
        'permission' => 'User can get page',
        'roles' => ['admin'],
        'postfix' => '*',
    ];

    public static function allRoutes(): array
    {
        return [
            //Auth
            self::$viewProfile,
            self::$changePassword,
            self::$resetPassword,
            self::$canRefresh,
            self::$canLogout,
            //2FA
            self::$generateSecret,
            self::$enable2FA,
            self::$disable2FA,
            self::$verify2FA,
            //Comment
            self::$getComments,
            self::$getCommentsSlug,
            self::$postComment,
            self::$updateComment,
            self::$deleteComment,
            //Category
            self::$createCategory,
            self::$updateCategory,
            self::$deleteCategory,
            //CourseCategory
            self::$createCourseCategory,
            self::$updateCourseCategory,
            self::$deleteCourseCategory,
            //Course
            self::$createCourse,
            self::$updateCourse,
            self::$deleteCourse,
            //Purchase
            self::$canPurchase,
            self::$getPurchases,
            //Enrollment
            self::$getEnrollments,
            self::$getEnrollmentsSlug,
            //Article
            self::$getArticles,
            self::$getArticle,
            self::$updateArticle,
            self::$createArticle,
            self::$deleteArticle,
            //Subscription
            self::$activeSubscriptions,
            self::$canSubscribe,
            self::$checkSubscription,
            //Page
            self::$createPage,
            self::$updatePage,
            self::$deletePage,
            self::$getPage,
            self::$getPages,
            self::$restorePage,
            //Category
            self::$createCategory,
            self::$updateCategory,
            self::$deleteCategory,
            //CourseCategory
            self::$createCourseCategory,
            self::$updateCourseCategory,
            self::$deleteCourseCategory,
            //Course
            self::$createCourse,
            self::$updateCourse,
            self::$deleteCourse,
            //Purchase
            self::$canPurchase,
            self::$getPurchases,
            //Enrollment
            self::$getEnrollments,
            //Article
            self::$getArticles,
            self::$getArticle,
            self::$updateArticle,
            self::$createArticle,
            self::$deleteArticle,
            //Subscription
            self::$activeSubscriptions,
            self::$canSubscribe,
            self::$checkSubscription,
            //Page
            self::$createPage,
            self::$updatePage,
            self::$deletePage,
            self::$getPage,
            self::$getPages,
            self::$restorePage,
        ];
    }

    public static function publicRoutes(): array
    {
        return self::$publicRoutes;
    }
}

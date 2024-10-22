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
        'prefix' => 'api/articles/',
        'postfix' => '*',
    ];

    public static array $postComment = [
        'path' => 'post-comment',
        'permission' => 'User can post comment',
        'roles' => ['admin', 'student'],
        'prefix' => 'api/articles/',

    ];

    public static array $updateComment = [
        'path' => 'update-comment',
        'permission' => 'User can update comment',
        'roles' => ['admin', 'student'],
        'prefix' => 'api/articles/',
    ];

    public static array $deleteComment = [
        'path' => 'delete-comment',
        'permission' => 'User can delete comment',
        'roles' => ['admin', 'student'],
        'prefix' => 'api/articles/',
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
            self::$postComment,
            self::$updateComment,
            self::$deleteComment,
        ];
    }

    public static function publicRoutes(): array
    {
        return self::$publicRoutes;
    }
}

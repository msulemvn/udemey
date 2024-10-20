<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        $this->app->bind(
            \App\Services\User\UserService::class
        );

        $this->app->bind(
            \App\Services\User\RegisterService::class
        );

        $this->app->bind(
            \App\Services\Course\CourseService::class
        );

        $this->app->bind(
            \App\Services\Category\CategoryService::class
        );

        $this->app->bind(
            \App\Services\CourseCategory\CourseCategoryService::class
        );

        $this->app->bind(
            \App\Services\Purchase\PurchaseService::class
        );

        $this->app->bind(
            \App\Services\Auth\TwoFactorService::class
        );

        $this->app->bind(
            \App\Services\Auth\AuthService::class
        );

        $this->app->bind(
            \App\Services\Comment\CommentService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return 'http://localhost:8080/reset-password?email=' . $notifiable->getEmailForPasswordReset() . '&token=' . $token;
        });
    }
}

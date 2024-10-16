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
            \App\Interfaces\User\UserServiceInterface::class,
            \App\Services\User\UserService::class
        );

        $this->app->bind(
            \App\Interfaces\User\RegisterServiceInterface::class,
            \App\Services\User\RegisterService::class
        );

        $this->app->bind(
            \App\Interfaces\Course\CourseServiceInterface::class,
            \App\Services\Course\CourseService::class
        );

        $this->app->bind(
            \App\Interfaces\Category\CategoryServiceInterface::class,
            \App\Services\Category\CategoryService::class
        );

        $this->app->bind(
            \App\Interfaces\CourseCategory\CourseCategoryServiceInterface::class,
            \App\Services\CourseCategory\CourseCategoryService::class
        );

        $this->app->bind(
            \App\Interfaces\Cart\CartServiceInterface::class,
            \App\Services\Cart\CartService::class
        );

        $this->app->bind(
            \App\Interfaces\Purchase\PurchaseServiceInterface::class,
            \App\Services\Purchase\PurchaseService::class
        );

        $this->app->bind(
            \App\Interfaces\Auth\TwoFactorServiceInterface::class,
            \App\Services\Auth\TwoFactorService::class
        );

        $this->app->bind(
            \App\Interfaces\Auth\AuthServiceInterface::class,
            \App\Services\Auth\AuthService::class
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

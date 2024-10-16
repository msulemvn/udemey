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
            \App\Interfaces\UserServiceInterface::class,
            \App\Services\User\UserService::class
        );

        $this->app->bind(
            \App\Interfaces\Cart\CartServiceInterface::class,
            \App\Services\User\Cart\CartService::class
        );

        $this->app->bind(
            \App\Interfaces\PurchaseServiceInterface::class,
            \App\Services\Purchase\CourseService::class
        );

        $this->app->bind(
            \App\Interfaces\Purchase\PurchaseServiceInterface::class,
            \App\Services\Purchase\PurchaseService::class
        );
<<<<<<< HEAD

        $this->app->bind(
            \App\Interfaces\Enrollment\EnrollmentServiceInterface::class,
            \App\Services\Enrollment\EnrollmentService::class
        );

        $this->app->bind(
            \App\Interfaces\Cart\CartServiceInterface::class,
            \App\Services\Cart\CartService::class
        );

        $this->app->bind(
            \App\Interfaces\Purchase\PurchaseServiceInterface::class,
            \App\Services\Purchase\PurchaseService::class
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

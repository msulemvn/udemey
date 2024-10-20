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

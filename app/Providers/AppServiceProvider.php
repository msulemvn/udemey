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
            \App\Services\UserService::class
        );

        $this->app->bind(
            \App\Interfaces\UserServiceInterface::class,
            \App\Services\UserService::class
        );

        $this->app->bind(
            \App\Interfaces\ProductServiceInterface::class,
            \App\Services\ProductService::class
        );

        $this->app->bind(
            \App\Interfaces\ApplicationServiceInterface::class,
            \App\Services\ApplicationService::class
        );

		$this->app->bind(
			\App\Interfaces\QuizRecordServiceInterface::class,
			\App\Services\QuizRecordService::class
		);

		$this->app->bind(
			\App\Interfaces\RegisterServiceInterface::class,
			\App\Services\RegisterService::class
		);

		$this->app->bind(
			\App\Interfaces\CourseServiceInterface::class,
			\App\Services\CourseService::class
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
			\App\Interfaces\PurchaseServiceInterface::class,
			\App\Services\PurchaseService::class
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

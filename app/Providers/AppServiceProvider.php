<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
            \App\Services\Auth\AuthService::class
        );

        $this->app->bind(
            \App\Services\Comment\CommentService::class
        );

        $this->app->bind(
            \App\Services\Menu\MenuService::class
        );

        $this->app->bind(
            \App\Services\MenuItem\MenuItemService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}

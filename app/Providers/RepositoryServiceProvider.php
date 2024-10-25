<?php

namespace App\Providers;

use App\Interfaces\MenuInterface;
use App\Repositories\MenuRepository;
use App\Interfaces\MenuItemInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\MenuItemRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MenuInterface::class, MenuRepository::class);
        $this->app->bind(MenuItemInterface::class, MenuItemRepository::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

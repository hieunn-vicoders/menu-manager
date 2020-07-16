<?php

namespace VCComponent\Laravel\Menu\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Menu\Repositories\ItemMenuRepository;
use VCComponent\Laravel\Menu\Repositories\ItemMenuRepositoryEloquent;
use VCComponent\Laravel\Menu\Repositories\MenuRepository;
use VCComponent\Laravel\Menu\Repositories\MenuRepositoryEloquent;
use VCComponent\Laravel\Menu\Services\Menu;

class MenuComponentProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind(MenuRepository::class, MenuRepositoryEloquent::class);
        App::bind(ItemMenuRepository::class, ItemMenuRepositoryEloquent::class);
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind("menu_manager", Menu::class);
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes.php');
        $this->publishes([
            __DIR__ . '/../../resources/sass/menu' => resource_path('sass/menu'),
            __DIR__ . '/../../resources/views'     => resource_path('views'),
            __DIR__ . '/../../config/menu.php'     => config_path('menu.php'),
        ]);

    }
}

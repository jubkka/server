<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Observers\PermissionObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрация Observer
        Permission::observe(PermissionObserver::class);
        //User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);
    }

    /**
     * Определяет, как загружаются маршруты для API.
     *
     * @return void
     */

     public function mapApiRoutes()
    {
        Route::prefix('api') // Префикс для API
            ->middleware('api') // Используем middleware 'api'
            ->group(base_path('routes/api.php')); // Загружаем маршруты из routes/api.php
    }
}

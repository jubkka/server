<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Observers\UserObserver;

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
        // Регистрация Observer для модели User
        User::observe(UserObserver::class);
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

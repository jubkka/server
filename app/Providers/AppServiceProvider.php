<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        //
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

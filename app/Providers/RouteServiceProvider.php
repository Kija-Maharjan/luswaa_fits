<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the application.
     */
    protected function mapWebRoutes()
    {
        if (file_exists(base_path('routes/web.php'))) {
            Route::middleware('web')->group(base_path('routes/web.php'));
        }
    }

    /**
     * Define the "api" routes for the application.
     */
    protected function mapApiRoutes()
    {
        if (file_exists(base_path('routes/api.php'))) {
            Route::prefix('api')->middleware('api')->group(base_path('routes/api.php'));
        }
    }
}

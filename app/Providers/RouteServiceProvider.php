<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // Register web routes (no changes here)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
    
            // Register API routes without middleware
            Route::prefix('api')
                ->group(base_path('routes/api.php'));  // Removed the 'api' middleware
        });
    }

}

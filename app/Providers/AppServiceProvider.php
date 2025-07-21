<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register custom middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
    }
}

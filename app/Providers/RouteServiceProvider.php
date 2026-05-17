<?php

namespace App\Providers;

// ... existing ...
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ... existing ...

        // +++ Register middleware alias +++
        Route::aliasMiddleware('admin.auth', \App\Http\Middleware\AdminAuth::class);
    }
}

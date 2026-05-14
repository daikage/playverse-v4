<?php

namespace App\Providers;

use App\Support\Tenancy\TenantManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(TenantManager::class, fn () => new TenantManager());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // +++ ADD THIS LINE +++
        Schema::defaultStringLength(191);
        // +++ END ADDITION +++
    }
}

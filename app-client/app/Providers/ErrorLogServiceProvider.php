<?php

namespace App\Providers;

use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Support\ServiceProvider;

class ErrorLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ErrorLogService::class, function ($app) {
            return new ErrorLogService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

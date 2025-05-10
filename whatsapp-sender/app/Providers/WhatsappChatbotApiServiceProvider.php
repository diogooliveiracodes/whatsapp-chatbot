<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\WhatsappApi\WhatsappApi;

class WhatsappChatbotApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsappApi::class, function ($app) {
            return new WhatsappApi();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(
            base_path('modules/WhatsappApi/Config/whatsappHeaders.php'),
            'whatsappHeaders'
        );
        $this->mergeConfigFrom(
            base_path('modules/WhatsappApi/Config/templateHelloWorld.php'),
            'templateHelloWorld'
        );
    }
}

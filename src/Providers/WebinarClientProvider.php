<?php

namespace Bloodlog\WebinarClient\Providers;

use Illuminate\Support\ServiceProvider;

class WebinarClientProvider extends ServiceProvider
{
    /**
     * Публикация конфига.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/webinar.php' => config_path('webinar.php'),
        ]);
    }

    /**
     * Объединение конфигов.
     */
    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__ . '/../../config/webinar.php', 'webinar');
    }
}

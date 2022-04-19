<?php

namespace Bloodlog\WebinarClient\Providers;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use Bloodlog\WebinarClient\WebinarClient;

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

        $this->registerClient();
    }

    /**
     * @return void
     */
    protected function registerClient()
    {
        $this->app->bind(WebinarClient::class, function () {
            return new WebinarClient(new HttpClient([
                'base_uri' => config('services.webinar.base_url'),
                'headers' => [
                    'x-auth-token' => config('services.webinar.token'),
                ],
            ]));
        });
    }
}

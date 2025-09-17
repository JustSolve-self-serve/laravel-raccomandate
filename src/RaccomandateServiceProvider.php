<?php

namespace JustSolve\Raccomandate;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;

class RaccomandateServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge and publish config
        $this->mergeConfigFrom(__DIR__ . '/../config/raccomandate.php', 'raccomandate');

        // Bind our service into the service container with auto-injected HTTP client
        $this->app->singleton(RaccomandateService::class, function ($app) {
            return new RaccomandateService($app->make(HttpFactory::class));
        });
    }

    public function boot()
    {
        // Publish config for the main application
        $this->publishes([
            __DIR__ . '/../config/raccomandate.php' => config_path('raccomandate.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}

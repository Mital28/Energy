<?php

namespace Energy\Providers;

use Illuminate\Support\ServiceProvider;
use Energy\Services\MatchService;

class MatchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Energy\Contracts\MatchContract', function ($app) {
            return new MatchService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Energy\Contracts\MatchContract'];
    }
}

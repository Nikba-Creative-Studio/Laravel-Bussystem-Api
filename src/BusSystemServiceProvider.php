<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi;

use Illuminate\Support\ServiceProvider;
use Nikba\LaravelBussystemApi\Contracts\BusSystemClientInterface;
use Nikba\LaravelBussystemApi\Services\BusSystemClient;

class BusSystemServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/bussystem.php',
            'bussystem'
        );

        $this->app->singleton(BusSystemClientInterface::class, function ($app) {
            return new BusSystemClient(
                config('bussystem.api_url'),
                config('bussystem.login'),
                config('bussystem.password'),
                config('bussystem.partner_id'),
                config('bussystem.timeout', 120)
            );
        });

        $this->app->alias(BusSystemClientInterface::class, 'bussystem');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/bussystem.php' => config_path('bussystem.php'),
            ], 'bussystem-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'bussystem-migrations');

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            BusSystemClientInterface::class,
            'bussystem'
        ];
    }
}
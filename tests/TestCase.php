<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests;

use Nikba\LaravelBussystemApi\BusSystemServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    protected function getPackageProviders($app): array
    {
        return [
            BusSystemServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        
        config()->set('bussystem.api_url', 'https://test-api.bussystem.eu/server');
        config()->set('bussystem.login', 'test_login');
        config()->set('bussystem.password', 'test_password');
        config()->set('bussystem.partner_id', 'test_partner');
        config()->set('bussystem.cache.enabled', false);
        config()->set('bussystem.logging.enabled', false);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
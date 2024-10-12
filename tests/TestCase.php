<?php

namespace Tests;

use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pan\Adapters\Laravel\Providers\PanServiceProvider;

use function Orchestra\Testbench\workbench_path;

#[WithMigration]
abstract class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     */
    protected function defineEnvironment($app): void
    {
        //
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            [workbench_path('database/migrations'), __DIR__.'/../database/migrations'],
        );
    }

    /**
     * Get package providers.
     *
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            PanServiceProvider::class,
        ];
    }
}

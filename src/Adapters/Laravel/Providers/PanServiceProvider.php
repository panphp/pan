<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Providers;

use Illuminate\Contracts\Http\Kernel as HttpContract;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pan\Adapters\Laravel\Console\Commands\InstallPanCommand;
use Pan\Adapters\Laravel\Console\Commands\PanCommand;
use Pan\Adapters\Laravel\Console\Commands\PanDeleteCommand;
use Pan\Adapters\Laravel\Console\Commands\PanFlushCommand;
use Pan\Adapters\Laravel\Http\Controllers\EventController;
use Pan\Adapters\Laravel\Http\Middleware\InjectJavascriptLibrary;
use Pan\Adapters\Laravel\Repositories\DatabaseAnalyticsRepository;
use Pan\Contracts\AnalyticsRepository;
use Pan\PanConfiguration;

/**
 * @internal
 */
final class PanServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->registerConfiguration();
        $this->registerRepositories();
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerRoutes();
        $this->registerPublishing();
    }

    /**
     * Register the package configuration.
     */
    private function registerConfiguration(): void
    {
        $this->app->bind(PanConfiguration::class, fn (): \Pan\PanConfiguration => PanConfiguration::instance());
    }

    /**
     * Register the package repositories.
     */
    private function registerRepositories(): void
    {
        $this->app->bind(AnalyticsRepository::class, DatabaseAnalyticsRepository::class);
    }

    /**
     * Register the package routes.
     */
    private function registerRoutes(): void
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(HttpContract::class);

        $kernel->pushMiddleware(InjectJavascriptLibrary::class);

        /** @var PanConfiguration $config */
        $config = $this->app->get(PanConfiguration::class);

        Route::prefix($config->toArray()['route_prefix'])->group(function (): void {
            Route::post('/events', [EventController::class, 'store']);
        });
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__.'/../../../../database/migrations' => database_path('migrations'),
            ], 'pan-migrations');
        }
    }

    /**
     * Register the package's commands.
     */
    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPanCommand::class,
                PanCommand::class,
                PanFlushCommand::class,
                PanDeleteCommand::class,
            ]);
        }
    }
}

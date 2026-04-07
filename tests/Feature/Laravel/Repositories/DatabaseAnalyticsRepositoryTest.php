<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Pan\PanConfiguration;

it('routes queries through the configured database connection', function (): void {
    config(['database.connections.secondary' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]]);

    Schema::connection('secondary')->create('pan_analytics', function ($table): void {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('impressions')->default(0);
        $table->unsignedBigInteger('hovers')->default(0);
        $table->unsignedBigInteger('clicks')->default(0);
    });

    PanConfiguration::databaseConnection('secondary');

    app(AnalyticsRepository::class)->increment('help-modal', EventType::CLICK);

    expect(DB::connection('secondary')->table('pan_analytics')->count())->toBe(1)
        ->and(DB::table('pan_analytics')->count())->toBe(0);
})->after(function (): void {
    DB::purge('secondary');
    PanConfiguration::databaseConnection(config('database.default'));
});

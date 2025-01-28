<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;

it('displays a message if there are no analytics', function (): void {
    $response = $this->artisan('pan:export');

    $response
        ->expectsOutputToContain('There are no analytics to export.')
        ->assertExitCode(0);
});

it('exports all analytics', function (): void {
    $analytics = app(AnalyticsRepository::class);

    $analytics->increment('dashboard', EventType::IMPRESSION);
    $analytics->increment('dashboard', EventType::IMPRESSION);
    $analytics->increment('dashboard', EventType::IMPRESSION);

    $analytics->increment('dashboard', EventType::HOVER);
    $analytics->increment('dashboard', EventType::HOVER);

    $analytics->increment('dashboard', EventType::CLICK);

    $exitCode = Artisan::call('pan:export');

    expect($exitCode)->toBe(0);
});

<?php

use Illuminate\Support\Facades\Artisan;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;

it('displays analytics even if they are empty', function (): void {
    $response = $this->artisan('pan');

    $response
        ->expectsOutputToContain('No analytics have been recorded yet. Get started by adding the [data-pan] attribute to your HTML elements.')
        ->assertExitCode(0);
});

it('displays all analytics', function (): void {
    $analytics = app(AnalyticsRepository::class);

    $analytics->increment('dashboard', EventType::IMPRESSION);
    $analytics->increment('dashboard', EventType::IMPRESSION);
    $analytics->increment('dashboard', EventType::IMPRESSION);

    $analytics->increment('dashboard', EventType::HOVER);
    $analytics->increment('dashboard', EventType::HOVER);

    $analytics->increment('dashboard', EventType::CLICK);

    $exitCode = Artisan::call('pan');

    expect($exitCode)->toBe(0);
});

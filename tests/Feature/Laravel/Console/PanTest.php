<?php

use Illuminate\Support\Facades\Artisan;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Pan\PanConfiguration;

it('displays analytics even if they are empty', function (): void {
    $response = $this->artisan('pan');

    $response
        ->expectsOutputToContain('No analytics have been recorded yet. Get started collecting analytics by adding the [data-pan="my-button"] attribute to your HTML elements.')
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

it('displays filtered analytics', function (): void {
    $analytics = app(AnalyticsRepository::class);

    $analytics->increment('dashboard', EventType::IMPRESSION);
    $analytics->increment('dashboard', EventType::IMPRESSION);
    $analytics->increment('dashboard', EventType::IMPRESSION);

    $analytics->increment('dashboard', EventType::HOVER);
    $analytics->increment('dashboard', EventType::HOVER);

    $analytics->increment('dashboard', EventType::CLICK);

    $analytics->increment('profile', EventType::IMPRESSION);
    $analytics->increment('profile', EventType::IMPRESSION);

    $exitCode = Artisan::call('pan --filter=profile');

    expect($exitCode)->toBe(0);
});

it('displays tenant specific analytics', function (): void {
    PanConfiguration::tenantField('team_id');
    PanConfiguration::tenantId(1);

    $exitCode = Artisan::call('pan --tenant=1');

    expect($exitCode)->toBe(0);
});

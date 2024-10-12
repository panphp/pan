<?php

use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;

it('may run even if there is no analytics', function (): void {
    $response = $this->artisan('pan:flush');

    $response->expectsOutputToContain('All analytics have been flushed.')->assertExitCode(0);

    $analytics = app(AnalyticsRepository::class);

    expect($analytics->all())->toHaveCount(0);
});

it('flushes all analytics', function (): void {
    $analytics = app(AnalyticsRepository::class);

    $analytics->increment('dashboard', EventType::IMPRESSION);

    expect($analytics->all())->toHaveCount(1);

    $response = $this->artisan('pan:flush');

    $response->expectsOutputToContain('All analytics have been flushed.')->assertExitCode(0)->run();

    expect($analytics->all())->toHaveCount(0);
});

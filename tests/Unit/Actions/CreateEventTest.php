<?php

use Pan\Actions\CreateEvent;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Pan\ValueObjects\Analytic;

it('increments the click event for the given analytic', function (): void {
    $action = app(CreateEvent::class);

    $action->handle('help-modal', EventType::CLICK);
    $action->handle('help-modal', EventType::CLICK);
    $action->handle('help-modal', EventType::HOVER);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 0, 'hovers' => 1, 'clicks' => 2],
    ]);
});

it('increments the hover and click events for the given analytic', function (): void {
    $action = app(CreateEvent::class);

    $action->handle('help-modal', [EventType::HOVER, EventType::CLICK]);
    $action->handle('help-modal', [EventType::HOVER, EventType::CLICK]);
    $action->handle('help-modal', EventType::IMPRESSION);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 1, 'hovers' => 2, 'clicks' => 2],
    ]);
});

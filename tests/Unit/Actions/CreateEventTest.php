<?php

use Pan\Actions\CreateEvent;
use Pan\Contracts\AnalyticsRepository;
use Pan\ValueObjects\Analytic;

it('increments the click event for the given analytic', function (): void {
    $action = app(CreateEvent::class);

    $action->handle('help-modal', 'click');
    $action->handle('help-modal', 'click');
    $action->handle('help-modal', 'hover');

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 0, 'hovers' => 1, 'clicks' => 2],
    ]);
});

it('ignores the click event for the given analytic if the event is invalid', function (): void {
    $action = app(CreateEvent::class);

    $action->handle('help-modal', 'invalid');

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([]);
});

it('ignores the click event for the given analytic if the event is not in the blueprint', function (): void {
    $action = app(CreateEvent::class);

    $action->handle('help-modal:click,impression', 'hover');

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([]);
});

<?php

use Illuminate\Support\Facades\DB;
use Pan\Contracts\AnalyticsRepository;
use Pan\ValueObjects\Analytic;

it('can create an analytic click event', function (): void {
    $response = $this->post('/pan/events', [
        'events' => [[
            'name' => 'help-modal',
            'type' => 'click',
        ]],
    ]);

    $response->assertStatus(204);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 0, 'hovers' => 0, 'clicks' => 1],
    ]);
});

it('can create an analytic hover event', function (): void {
    $response = $this->post('/pan/events', [
        'events' => [[
            'name' => 'help-modal',
            'type' => 'hover',
        ]],
    ]);

    $response->assertStatus(204);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 0, 'hovers' => 1, 'clicks' => 0],
    ]);
});

it('can create an analytic impression event', function (): void {
    $response = $this->post('/pan/events', [
        'events' => [[
            'name' => 'help-modal',
            'type' => 'impression',
        ]],
    ]);

    $response->assertStatus(204);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 1, 'hovers' => 0, 'clicks' => 0],
    ]);
});

it('can create an analytic impression event and click event', function (): void {
    $response = $this->post('/pan/events', [
        'events' => [
            [
                'name' => 'help-modal',
                'type' => 'impression',
            ],
            [
                'name' => 'help-modal',
                'type' => 'click',
            ],
        ],
    ]);

    $response->assertStatus(204);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([
        ['id' => 1, 'name' => 'help-modal', 'impressions' => 1, 'hovers' => 0, 'clicks' => 1],
    ]);
});

it('does not create an analytic event if the event is invalid', function (): void {
    $response = $this->post('/pan/events', [
        'events' => [[
            'name' => 'help-modal',
            'type' => 'invalid',
        ]],
    ]);

    $response->assertStatus(302)->assertSessionHasErrors([
        'events.0.type' => 'The selected events.0.type is invalid.',
    ]);

    $analytics = array_map(fn (Analytic $analytic): array => $analytic->toArray(), app(AnalyticsRepository::class)->all());

    expect($analytics)->toBe([]);
});

it('does handle gracefully when there is more than 50 analytics created', function (): void {
    DB::table('pan_analytics')->insert(array_map(fn (int $index): array => [
        'name' => "help-modal-$index",
        'impressions' => 0,
        'hovers' => 0,
        'clicks' => 0,
    ], range(1, 49)));

    expect(DB::table('pan_analytics')->count())->toBe(49);

    $response = $this->post('/pan/events', [
        'events' => [[
            'name' => 'help-modal',
            'type' => 'click',
        ]],
    ]);

    $response->assertStatus(204);

    expect(DB::table('pan_analytics')->count())->toBe(50);

    $response = $this->post('/pan/events', [
        'events' => [[
            'name' => 'help-modal',
            'type' => 'click',
        ]],
    ]);

    $response->assertStatus(204);

    expect(DB::table('pan_analytics')->count())->toBe(50);
});

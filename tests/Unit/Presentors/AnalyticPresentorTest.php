<?php

use Pan\Presentors\AnalyticPresentor;
use Pan\ValueObjects\Analytic;

it('present an analytic', function (): void {
    $analytic = new Analytic(1, 'help-modal', 1, 1, 1);

    $presentor = new AnalyticPresentor;

    expect($presentor->present($analytic))->toBe([
        'id' => '#1',
        'name' => 'help-modal',
        'impressions' => '1',
        'hovers' => '1 (100.0%)',
        'clicks' => '1 (100.0%)',
    ]);
});

it('present an analytic with 0 impressions', function (): void {
    $analytic = new Analytic(1, 'help-modal', 0, 1, 1);

    $presentor = new AnalyticPresentor;

    expect($presentor->present($analytic))->toBe([
        'id' => '#1',
        'name' => 'help-modal',
        'impressions' => '0',
        'hovers' => '1 (Infinity%)',
        'clicks' => '1 (Infinity%)',
    ]);
});

it('present an analytic with 0 hovers', function (): void {
    $analytic = new Analytic(1, 'help-modal', 1, 0, 1);

    $presentor = new AnalyticPresentor;

    expect($presentor->present($analytic))->toBe([
        'id' => '#1',
        'name' => 'help-modal',
        'impressions' => '1',
        'hovers' => '0 (0.0%)',
        'clicks' => '1 (100.0%)',
    ]);
});

it('present an analytic with 0 clicks', function (): void {
    $analytic = new Analytic(1, 'help-modal', 1, 1, 0);

    $presentor = new AnalyticPresentor;

    expect($presentor->present($analytic))->toBe([
        'id' => '#1',
        'name' => 'help-modal',
        'impressions' => '1',
        'hovers' => '1 (100.0%)',
        'clicks' => '0 (0.0%)',
    ]);
});

it('presents huge numbers', function (): void {
    $analytic = new Analytic(1, 'help-modal', 1000000, 1000000, 1000000);

    $presentor = new AnalyticPresentor;

    expect($presentor->present($analytic))->toBe([
        'id' => '#1',
        'name' => 'help-modal',
        'impressions' => '1,000,000',
        'hovers' => '1,000,000 (100.0%)',
        'clicks' => '1,000,000 (100.0%)',
    ]);
});

it('presents huge numbers with 0 impressions', function (): void {
    $analytic = new Analytic(1, 'help-modal', 0, 1000000, 1000000);

    $presentor = new AnalyticPresentor;

    expect($presentor->present($analytic))->toBe([
        'id' => '#1',
        'name' => 'help-modal',
        'impressions' => '0',
        'hovers' => '1,000,000 (Infinity%)',
        'clicks' => '1,000,000 (Infinity%)',
    ]);
});

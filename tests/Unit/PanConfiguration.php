<?php

use Pan\PanConfiguration;

it('have a max of 100 analytics by default', function (): void {
    $config = PanConfiguration::instance();

    expect($config->toArray())->toBe([
        'max' => 100,
        'allowed' => [],
    ]);
});

it('can set the max number of analytics to store', function (): void {
    $config = PanConfiguration::instance();

    $config->maxAnalytics(50);

    expect($config->toArray())->toBe([
        'max' => 50,
        'allowed' => [],
    ]);
});

it('can set the max number of analytics to unlimited', function (): void {
    $config = PanConfiguration::instance();

    $config->unlimitedAnalytics();

    expect($config->toArray())->toBe([
        'max' => PHP_INT_MAX,
        'allowed' => [],
    ]);
});

it('can set the allowed analytics names to store', function (): void {
    $config = PanConfiguration::instance();

    $config->allowed(['help-modal', 'contact-modal']);

    expect($config->toArray())->toBe([
        'max' => 100,
        'allowed' => ['help-modal', 'contact-modal'],
    ]);
});

it('sets an empty array of allowed analytics names by default', function (): void {
    $config = PanConfiguration::instance();

    expect($config->toArray())->toBe([
        'max' => 100,
        'allowed' => [],
    ]);
});

it('may reset the configuration to its default values', function (): void {
    $config = PanConfiguration::instance();

    $config->maxAnalytics(99);
    $config->allowed(['help-modal', 'contact-modal']);

    expect($config->toArray())->toBe([
        'max' => 99,
        'allowed' => ['help-modal', 'contact-modal'],
    ]);

    $config->reset();

    expect($config->toArray())->toBe([
        'max' => 50,
        'allowed' => [],
    ]);
});

<?php

use Illuminate\Support\Facades\Artisan;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;

it('displays a message if there are no analytics', function (): void {
    $response = $this->artisan('pan:export');

    $response
        ->expectsOutputToContain('There are no analytics to export.')
        ->assertExitCode(0);
});

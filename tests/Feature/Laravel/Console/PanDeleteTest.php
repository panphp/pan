<?php

use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Symfony\Component\Console\Exception\RuntimeException;

it('deletes a specific analytic by ID', function (): void {
    $analytics = app(AnalyticsRepository::class);

    $analytics->increment('dashboard', EventType::IMPRESSION);

    $id = $analytics->all()[0]->id;

    $this->artisan('pan:delete', ['id' => $id])
        ->expectsOutput('Analytic has been deleted.')
        ->assertExitCode(0);

    expect($analytics->all())->toHaveCount(0);
});

it('fails when no argument is provided', function (): void {
    expect(fn () => $this->artisan('pan:delete')->run())
        ->toThrow(RuntimeException::class, 'Not enough arguments (missing: "id").');
});

it('handles non-existent analytic gracefully', function (): void {
    $this->artisan('pan:delete', ['id' => 9999])
        ->expectsOutput('Record not found or already deleted.')
        ->assertExitCode(0);
});

it('handles invalid ID gracefully', function (): void {
    $this->artisan('pan:delete', ['id' => 'invalid'])
        ->expectsOutput('Invalid ID provided.')
        ->assertExitCode(0);
});

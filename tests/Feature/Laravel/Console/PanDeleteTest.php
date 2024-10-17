<?php

use Pan\Contracts\AnalyticsRepository;

beforeEach(function (): void {
    $this->repository = mock(AnalyticsRepository::class)->makePartial();
    app()->instance(AnalyticsRepository::class, $this->repository);
});

it('deletes a specific analytic by ID', function (): void {
    $this->repository
        ->expects('delete')
        ->with(1)
        ->once()
        ->andReturn('Analytic has been deleted.');

    $this->artisan('pan:delete', ['id' => 1])
        ->assertExitCode(0)
        ->expectsOutput('Analytic has been deleted.');
});

it('fails when no argument is provided', function (): void {
    $this->artisan('pan:delete')
        ->assertExitCode(1)
        ->expectsOutput('Not enough arguments (missing: "id").');
});

it('handles non-existent analytic gracefully', function (): void {
    $this->repository
        ->expects('delete')
        ->with(26)
        ->once()
        ->andReturn('Analytic not found or already deleted.');

    $this->artisan('pan:delete', ['id' => 26])
        ->assertExitCode(0)
        ->expectsOutput('Analytic not found or already deleted.');
});

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
        ->andReturn(1);

    $this->artisan('pan:delete', ['id' => 1])
        ->expectsOutput('Analytic has been deleted.')
        ->assertExitCode(0);
});

it('fails when no argument is provided', function (): void {
    $this->artisan('pan:delete')
        ->expectsOutput('Not enough arguments (missing: "id").')
        ->assertExitCode(1);
});

it('handles non-existent analytic gracefully', function (): void {
    $this->repository
        ->expects('delete')
        ->with(26)
        ->once()
        ->andReturn(0);

    $this->artisan('pan:delete', ['id' => 26])
        ->expectsOutput('Record not found or already deleted.')
        ->assertExitCode(0);
});

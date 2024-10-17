<?php

use Pan\Contracts\AnalyticsRepository;

beforeEach(function (): void {
    $this->repository = mock(AnalyticsRepository::class)->makePartial();
    app()->instance(AnalyticsRepository::class, $this->repository);
});

it('deletes a specific analytic by ID', function (): void {
    $this->repository->shouldReceive('delete')->once()->with(1)->andReturn();

    $this->artisan('pan:delete', ['id' => 1])
        ->assertExitCode(0)
        ->expectsOutputToContain('Analytic has been deleted.');
});

it('fails when no argument is provided', function (): void {
    $this->artisan('pan:delete')
        ->assertExitCode(1)
        ->assertOutputContains('Not enough arguments (missing: "id").');
});

<?php

beforeEach(function (): void {
    $this->repository = mock(AnalyticsRepository::class)->makePartial();
    app()->instance(AnalyticsRepository::class, $this->repository);
});

it('deletes a specific analytic by ID', function (): void {
    $this->repository->shouldReceive('delete')->once()->with(1)->andReturn();

    $command = artisan('pan:delete', ['--id' => 1]);

    expect($command->exitCode())->toBe(0);
    expect($command->output())->toContain('Analytic has been deleted.');
});

it('fails when no option is provided', function (): void {
    $command = artisan('pan:delete');

    expect($command->exitCode())->toBe(1);
    expect($command->output())->toContain('Please specify --id option.');
});

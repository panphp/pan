<?php

use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    Schema::dropIfExists('pan_analytics');
});

afterEach(function (): void {
    $existingMigrations = glob(database_path('migrations/*_create_pan_tables.php')) ?? [];

    foreach ($existingMigrations as $migration) {
        unlink($migration);
    }
});

it('publishes the package migrations', function (): void {
    $response = $this->artisan('install:pan');

    $response
        ->expectsConfirmation('Would like to run the migrations now?', 'yes')
        ->expectsOutputToContain('Publishing Pan migrations')
        ->expectsOutputToContain('Pan was installed successfully. Now you can track events by adding the [data-pan] attribute to your HTML elements. You can view analytics running the [artisan pan] command.')
        ->assertExitCode(0)
        ->run();

    $existingMigration = glob(database_path('migrations/*_create_pan_tables.php'));

    $this->assertTrue(is_array($existingMigration) && $existingMigration !== []);
});

it('wont republish the package migrations', function (): void {
    $response = $this->artisan('install:pan');

    $response
        ->expectsConfirmation('Would like to run the migrations now?', 'yes')
        ->expectsOutputToContain('Publishing Pan migrations')
        ->expectsOutputToContain('Pan was installed successfully. Now you can track events by adding the [data-pan] attribute to your HTML elements. You can view analytics running the [artisan pan] command.')
        ->assertExitCode(0)
        ->run();

    $response = $this->artisan('install:pan');

    $response
        ->expectsOutputToContain('Pan was installed successfully. Now you can track events by adding the [data-pan] attribute to your HTML elements. You can view analytics running the [artisan pan] command.')
        ->assertExitCode(0)
        ->run();

    $existingMigration = glob(database_path('migrations/*_create_pan_tables.php'));
    dump($existingMigration);

    $this->assertTrue(is_array($existingMigration) && count($existingMigration) === 1);
});

it('does not run the migrations if the user declines', function (): void {
    $response = $this->artisan('install:pan');

    $response
        ->expectsConfirmation('Would like to run the migrations now?', 'no')
        ->expectsOutputToContain('Publishing Pan migrations')
        ->expectsOutputToContain('Pan was installed successfully. Now you can track events by adding the [data-pan] attribute to your HTML elements. You can view analytics running the [artisan pan] command.')
        ->assertExitCode(0)
        ->run();

    $existingMigration = glob(database_path('migrations/*_create_pan_tables.php'));

    $this->assertTrue(is_array($existingMigration) && $existingMigration !== []);
});

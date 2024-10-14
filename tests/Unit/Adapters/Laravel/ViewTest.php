<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('does not cause `assertViewIs` to fail', function (): void {
    $path = __DIR__.'/../../../Fixtures/example.blade.php';

    Route::get('/example', fn () => view()->file($path));

    $this->get('/example')
        ->assertOk()
        ->assertViewIs($path);
})->todo();

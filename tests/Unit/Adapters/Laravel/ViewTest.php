<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('does not cause `assertViewIs` to fail', function (): void {
    $path = __DIR__.'/../../../Fixtures/example.blade.php';
    $view = view()->file($path);

    Route::get('/example', fn () => $view);

    $testResponse = $this->get('/example')
        ->assertOk()
        ->assertViewIs($path);

    expect($testResponse->original)->toBe($view);
});

it('does not cause `assertViewHasData` to fail', function (): void {
    $path = __DIR__.'/../../../Fixtures/example.blade.php';
    $view = view()->file($path)->with('foo', 'bar');

    Route::get('/example', fn () => $view);

    $testResponse = $this->get('/example')
        ->assertOk()
        ->assertViewHas('foo', 'bar');

    expect($testResponse->original)->toBe($view);
});

it('does keep the original content untouched', function (): void {
    $content = '<html><body>Hello, world!</body></html>';
    $response = response($content);

    Route::get('/example', fn () => $response);

    $testResponse = $this->get('/example')
        ->assertOk()
        ->assertSee('Hello, world!');

    expect($response->getContent())->toContain('=window.__pan')
        ->and($testResponse->original)->toBe($content);
});

<?php

use Illuminate\Support\Facades\Route;

it('does inject the javascript library', function (): void {
    Route::get('/', fn (): string => <<<'HTML'
        <html lang="en">
            <head>
                <title>My App</title>
            </head>
            <body>
                <h1>Welcome to my app</h1>
            </body>
        </html>
        HTML
    );

    session()->put('_token', '_TEST_CSRF_TOKEN_');

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('script')
        ->assertSee('_TEST_CSRF_TOKEN_');
});

it('does not inject the javascript library if the content type is not text/html', function (): void {
    Route::get('/', fn () => response('Hello, World!')->header('Content-Type', 'text/plain'));

    session()->put('_token', '_TEST_CSRF_TOKEN_');

    $response = $this->get('/');

    $response->assertOk()
        ->assertDontSee('script')
        ->assertDontSee('_TEST_CSRF_TOKEN_');
});

it('does not inject the javascript library if the returned body is not the full html', function (): void {
    Route::get('/', fn (): string => <<<'HTML'
        <div>
            <h1>Welcome to my app</h1>
        </div>
        HTML
    );

    session()->put('_token', '_TEST_CSRF_TOKEN_');

    $response = $this->get('/');

    $response->assertOk()
        ->assertDontSee('script')
        ->assertDontSee('_TEST_CSRF_TOKEN_');
});

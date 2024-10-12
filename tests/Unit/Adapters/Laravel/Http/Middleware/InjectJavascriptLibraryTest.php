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

    $response = $this->get('/');

    $response->assertOk()->assertSee('script');
});

it('does not inject the javascript library if the content type is not text/html', function (): void {
    Route::get('/', fn () => response('Hello, World!')->header('Content-Type', 'text/plain'));

    $response = $this->get('/');

    $response->assertOk()->assertDontSee('script');
});

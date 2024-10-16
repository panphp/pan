<?php

use Pan\Actions\CreateEvent;
use Pan\Enums\EventType;

function getPath(): string
{
    $basePath = '/pan';
    $configPath = config('pan.ui.path', '/');

    return $basePath.$configPath;
}

it('checks if the route is working', function (): void {
    $path = getPath();

    $this->get($path)->assertOk();
});

it('checks if the route is working with query', function (): void {
    $path = getPath();

    $this->get($path.'?q=foo')->assertOk();
});

it('has Empty data message', function (): void {
    $path = getPath();

    $this->get($path)->assertSee('No analytics have been recorded yet.');
});

it('has Empty data message with query', function (): void {
    $path = getPath();

    $this->get($path.'?q=foo')->assertSee('No analytics have been recorded yet.');
});

it('has Data', function (): void {
    $path = getPath();

    $action = app(CreateEvent::class);

    $action->handle('help-modal', EventType::CLICK);
    $action->handle('help-modal', EventType::CLICK);
    $action->handle('help-modal', EventType::HOVER);

    $response = $this->get($path);

    $response->assertSee('help-modal');
});

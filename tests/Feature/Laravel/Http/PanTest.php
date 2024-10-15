<?php

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

it('checks if the route generates the table', function (): void {
    $path = getPath();

    $this->get($path)->assertSee('#')->assertSee('Name')->assertSee('Impressions')->assertSee('Hovers')->assertSee('Clicks');
});

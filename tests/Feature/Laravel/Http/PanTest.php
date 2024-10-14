<?php

it('checks if the route is working', function (): void {
    $this->get('/pan')->assertOk();
});

it('checks if the route generates the table', function (): void {
    $this->get('/pan')->assertSee('#')->assertSee('Name')->assertSee('Impressions')->assertSee('Hovers')->assertSee('Clicks');
});

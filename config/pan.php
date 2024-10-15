<?php

return [
    'ui' => [
        // Enable or disable the Pan UI.
        'enabled' => env('PAN_UI_ENABLED', true),

        // The path to the Pan UI. Default: /pan
        'path' => env('PAN_UI_PATH', '/'),

        // Middlewares to use for the Pan UI path. Example: ['auth']
        'path_middlewares' => [],
    ],
];

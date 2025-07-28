<?php

declare(strict_types=1);

return [
    'token' => env('TELEPATH_TOKEN'),
    'base_uri' => env('TELEPATH_BASE_URL', 'https://api.TELEPATH.org'),

    /*
     * Routes path
     */
    'routes' => base_path(env('TELEPATH_ROUTES', 'routes/TELEPATH.php')),

    'profile' => 'default',

    'profiles' => [
        'default' => [
            'offset' => (int) env('TELEPATH_OFFSET', 0),
            'timeout' => (int) env('TELEPATH_TIMEOUT', 30),
            'allowed_updates' => explode(',', env('TELEPATH_ALLOWED_UPDATES', '*')),
        ],
    ],
];

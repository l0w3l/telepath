<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telepath Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration of the Telepath package.
    | You can set your bot token and other settings here.
    |
    */

    'token' => env('TELEPATH_TOKEN'),
    'base_uri' => env('TELEPATH_BASE_URL', 'https://api.telegram.org'),

    /*
     * Routes path
     */
    'routes' => base_path(env('TELEPATH_ROUTES', 'routes/telegram.php')),

    'profile' => 'default',

    'profiles' => [
        'default' => [
            'offset' => (int) env('TELEPATH_OFFSET', 0),
            'timeout' => (int) env('TELEPATH_TIMEOUT', 30),
            'allowed_updates' => explode(',', env('TELEPATH_ALLOWED_UPDATES', '*')),
        ],
    ],
];

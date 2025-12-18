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

    'base_uri' => env('TELEPATH_BASE_URL', 'https://api.telegram.org'),
    'conversation' => [
        'ttl' => (int) env('TELEPATH_CONVERSATION_TIMEOUT', 60),
    ],

    /*
     * Routes path
     */
    'routes' => base_path(env('TELEPATH_ROUTES', 'routes/telegram.php')),

    'profile' => 'default',

    /**
     * see @link \Lowel\Telepath\Config\Profile
     */
    'profiles' => [
        'default' => [
            'token' => env('TELEPATH_TOKEN'),
            'offset' => (int) env('TELEPATH_OFFSET', 0),
            'limit' => (int) env('TELEPATH_LIMIT', 100),
            'timeout' => (int) env('TELEPATH_TIMEOUT', 30),
            'allowed_updates' => explode(',', env('TELEPATH_ALLOWED_UPDATES', '*')),

            'whitelist' => array_map(fn ($x) => (int) $x, explode(',', env('TELEPATH_ADMINS', ''))),
            'blacklist' => array_map(fn ($x) => (int) $x, explode(',', env('TELEPATH_BANNED', ''))),

            // will send report about unhandled exceptions to the given chat_id instance (chat or dm)
            'chat_id_fallback' => (int) env('TELEPATH_CHAT_ID_FALLBACK', null),
        ],
    ],
];

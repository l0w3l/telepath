<?php

use Lowel\Telepath\Enums\ParseModeEnum;

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
     * Flag to enable SpiritBoxAsync
     *
     * Require active queue:work process
     */
    'async' => env('TELEPATH_ASYNC', false),

    /**
     * see @link \Lowel\Telepath\Config\Profile
     */
    'profiles' => [
        'default' => [
            'token' => env('TELEPATH_TOKEN'),
            'username' => env('TELEPATH_USERNAME', ''),
            'offset' => (int) env('TELEPATH_OFFSET', 0),
            'limit' => (int) env('TELEPATH_LIMIT', 100),
            'timeout' => (int) env('TELEPATH_TIMEOUT', 30),
            'allowed_updates' => env('TELEPATH_ALLOWED_UPDATES', '*'),

            'parse_mode' => ParseModeEnum::MARKDOWN->value,

            // todo: currently works only in webhook
            // BE CAREFUL: enabling this option may lead to lost updates if your bot cannot process them in time
            'repeat_after_exception' => (int) env('TELEPATH_REPEAT_AFTER_EXCEPTION', 1),
            'timeout_after_exception' => (int) env('TELEPATH_TIMEOUT_AFTER_EXCEPTION', 5),

            'whitelist' => env('TELEPATH_ADMINS', ''),
            'blacklist' => env('TELEPATH_BANNED', ''),

            // will send report about unhandled exceptions to the given chat_id instance (chat or dm)
            'chat_id_fallback' => (int) env('TELEPATH_CHAT_ID_FALLBACK', null),
        ],
    ],
];

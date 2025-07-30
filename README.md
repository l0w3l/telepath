# Telepath: Telegram bot SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lowel/telepath.svg?style=flat-square)](https://packagist.org/packages/l0w3l/telepath)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/l0w3l/telepath/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/l0w3l/telepath/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/l0w3l/telepath/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/l0w3l/telepath/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lowel/telepath.svg?style=flat-square)](https://packagist.org/packages/l0w3l/telepath)

Telegram bot SDK for Laravel inspired by [vjik/telegram-bot-api](https://github.com/vjik/telegram-bot-api). 

SDK supports routes and long pooling \ webhook handling

## Installation

You can install the package via composer:

```bash
composer require lowel/telepath
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="telepath-config"
```

This is the contents of the published config file:

```php
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
```
## Usage

```php
use Lowel\Telepath\Facades\Telepath;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

Telepath::middleware(function (TelegramBotApi $telegramBotApi, Update $update, callable $callback) {
    logger()->info('Middleware in');
    $callback();
    logger()->info('Middleware out');    
})->group(function () {
    Telepath::on(function (TelegramBotApi $telegramBotApi, Update $update) {
        $telegramBotApi->sendMessage($update->getMessage()->getChat()->getId(), 'Hello, world!');
    }, pattern: '/start');

    Telepath::on(function (TelegramBotApi $telegramBotApi, Update $update) {
        $message = $update->getMessage();
        if ($message) {
            $telegramBotApi->sendMessage($message->getChat()->getId(), $message->getText());
        }
    }, pattern: '/echo');
});
```

Start up:

```bash
php artisan telepath:run
```

Or using webhook:

```php
php artisan telepath:hook:set https://your-domain.com/api/webhook
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

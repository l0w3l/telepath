# Telepath: Telegram bot SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lowel/telepath.svg?style=flat-square)](https://packagist.org/packages/lowel/telepath)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/l0w3l/telepath/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/l0w3l/telepath/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/l0w3l/telepath/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/l0w3l/telepath/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lowel/telepath.svg?style=flat-square)](https://packagist.org/packages/lowel/telepath)

Telegram bot SDK for Laravel inspired by [vjik/telegram-bot-api](https://github.com/vjik/telegram-bot-api). 

SDK supports routes and long pooling \ webhook handling

## How To Install

Install package via composer and publish config file:

```bash
composer require lowel/telepath
```
```bash
php artisan vendor:publish --tag="telepath-config"
```

Then you need to create *routes/telegram.php* file and provide bot key into your .env file: 

```bash
touch routes/telegram.php
```
```.dotenv
TELEPATH_TOKEN=<YOUR_TOKEN_HERE>
```

That's it! now you can handle Update request in your telegram.php file.

## Usage

```php
use Lowel\Telepath\Facades\Telepath;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;

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

## Documentation

1. [Installation](#Installation)
2. [Configuration](#Configuration)
3. [Handlers](#Handlers)
4. [Conversations](#Conversations)
5. [Keyboards](#Keyboards)
6. [Exceptions](#Exceptions)
7. [Tests](#Tests)

### [Installation](#Documentation)

You can install the package via composer and publish configuration file to your existing project:

```bash
composer require lowel/telepath
```

```bash
php artisan vendor:publish --tag="telepath-config"
```

Then you need to create *routes/telegram.php* file and provide bot key into your .env file:

```bash
touch routes/telegram.php
```
```.dotenv
TELEPATH_TOKEN=<YOUR_TOKEN_HERE>
```

That's it! now you can handle Update request in your *telegram.php* file using [Telepath](src/Core/Router/TelegramRouterInterface.php) facade.

### [Configuration](#Documentation)

Below you can see how default configuration file looks like:

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
            'allowed_updates' => env('TELEPATH_ALLOWED_UPDATES', '*'),

            'whitelist' => env('TELEPATH_ADMINS', ''),
            'blacklist' => env('TELEPATH_BANNED', ''),

            // will send report about unhandled exceptions to the given chat_id instance (chat or dm)
            'chat_id_fallback' => (int) env('TELEPATH_CHAT_ID_FALLBACK', null),
        ],
    ],
];
```

The only one necessary thing is the *token* field. But lets try looks on all fields little closer:

- *base_uri* - telegram api default domain (you can change if you are using [self-hosted solution](https://github.com/tdlib/telegram-bot-api));
- *conversation* - settings for dialog conversation that use memory to handle request in async mode:
  - *ttl* - decide how long conversation memory would work for each conversation message;
- *routes* - path to the routes handlers definition context (default *routes/telegram.php*);
- *profile* - default telegram bot profile name;
- *profiles* - list of telegram bot profiles;
  - *token* - telegram bot token that you receive from [@BotFather](https://t.me/BotFather) (REQUIRED, pass throw TELEPATH_TOKEN env field);
  - *offset* - telegram getUpdates offset field (default 0);
  - *limit* - telegram getUpdates limit field (default 100);
  - *timeout* - telegram getUpdates timeout field (default 30);
  - *allowed_updates* - telegram getUpdates allowed_updates field (default '*');
  - *whitelist* - comma separated list of user IDs that are allowed to interact with the bot (default empty);
  - *blacklist* - comma separated list of user IDs that are not allowed to interact with the bot (default empty);
  - *chat_id_fallback* - chat ID where unhandled exceptions will be reported (default null).

### [Handlers](#Documentation)

You can define handlers in your *routes/telegram.php* file using Telepath facade:

```php
use Lowel\Telepath\Facades\Telepath;

Telepath::on(function (TelegramBotApi $telegramBotApi, Update $update) {
    $telegramBotApi->sendMessage($update->getMessage()->getChat()->getId(), 'Hello, world!');
}, pattern: '/start');
```

Also you can use middleware to handle request before and after main handler:

```php
Telepath::middleware(function (TelegramBotApi $telegramBotApi, Update $update, callable $callback) {
    logger()->info('Middleware in');
    $callback();
    logger()->info('Middleware out');
})->group(function () {
    Telepath::on(function (TelegramBotApi $telegramBotApi, Update $update) {
        $telegramBotApi->sendMessage($update->getMessage()->getChat()->getId(), 'Hello, world!');
    }, pattern: '/start');
});
```

If you want to store Handlers and Middlewares as separated files you can generate them using commands below:

- Handlers
```bash
php artisan telepath:make:handler StartHandler
```
- Middlewares
```bash
php artisan telepath:make:middleware LogMiddleware
```

### [Conversations](#Conversations)

Conversations in Telepath simple as javascript Promises. You can generate a conversation using artisan:

```bash
php artisan telepath:make:conversation SampleConversation
```

And then use it in your handlers:

```php
use Lowel\Telepath\Facades\Telepath;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;

Telepath::on(function (TelegramBotApi $telegramBotApi, Update $update) {
//
}, pattern: '/start-conversation')
    ->conversation(SampleConversation::class);
```

### [Keyboards](#Documentation)

Telepath supports both inline and reply keyboards. You can create keyboards using the following artisan commands:

- Inline Keyboard
```bash
php artisan telepath:make:inline-keyboard SampleInlineKeyboard
```
- Reply Keyboard
```bash
php artisan telepath:make:reply-keyboard SampleReplyKeyboard
```

And then, after describing your keyboard layout in the generated files, you can use them in your handlers like this:

```php
use Lowel\Telepath\Facades\Telepath;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;
use Lowel\Telepath\Keyboards\InlineKeyboard\SampleInlineKeyboard;

Telepath::on(function (TelegramBotApi $telegramBotApi, Update $update) {
    $keyboard = new SampleInlineKeyboard();
    $telegramBotApi->sendMessage(
        Extrasense::chat()->id,
        'Choose an option:',
        replyMarkup: $keyboard->make()->build()
    );
}, pattern: '/keyboard');

Telepath::keyboard(SampleInlineKeyboard::class);
```

### [Exceptions](#Documentation)

Telepath has ExceptionHandler component. That allows you to catch unhandled exceptions and process them in your own way - just define wraps into your AppServiceProvider:

```php
\Lowel\Telepath\Facades\Paranormal::wrap(function (Update $update, Throwable $e) {
    // Your code that may throw exceptions
})
```

By default Telepath will send unhandled exceptions report to the chat_id defined in the configuration file.

### [Tests](#Documentation)

I am strive to coverage my code as much as possible with tests. But its almost features tests only. I hope its will changed soon:

```bash
php artisan test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

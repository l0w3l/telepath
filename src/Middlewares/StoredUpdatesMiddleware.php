<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares;

use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Exceptions\StoredUpdatesTableNotFoundException;
use Lowel\Telepath\Models\TelepathStoredUpdate;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

class StoredUpdatesMiddleware implements TelegramMiddlewareInterface
{
    public function __invoke(TelegramBotApi $telegram, Update $update, callable $callback): void
    {
        try {
            (new TelepathStoredUpdate([
                'instance' => $update,
            ]))->save();
        } catch (Throwable $exception) {
            throw new StoredUpdatesTableNotFoundException(
                'telepath_stored_updates table not found! Please use php artisan vendor:publish --tag=telepath-migrations && php artisan migrate',
                $exception->getCode(),
                $exception
            );
        }

        $callback();
    }
}

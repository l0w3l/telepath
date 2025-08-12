<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares;

use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Exceptions\StoredUpdatesTableNotFoundException;
use Lowel\Telepath\Models\TelepathStoredUpdate;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Middleware that stores updates in the telepath_stored_updates table.
 * It allows the next middleware or handler to be called after storing the update.
 */
class StoredUpdatesMiddleware implements TelegramMiddlewareInterface
{
    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        try {
            (new TelepathStoredUpdate([
                'instance' => $update,
            ]))->save();
        } catch (Throwable $exception) {
            throw new StoredUpdatesTableNotFoundException(
                'telepath_stored_updates table not found! Please use php artisan vendor:publish --tag=telepath-migrations && php artisan migrate',
                (int) $exception->getCode(),
                $exception
            );
        }

        $next();
    }
}

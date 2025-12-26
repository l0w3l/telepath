<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares;

use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Exceptions\StoredUpdatesTableNotFoundException;
use Lowel\Telepath\Models\TelepathStoredUpdate;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;
use Throwable;

/**
 * Middleware that stores updates in the telepath_stored_updates table.
 * It allows the next middleware or handler to be called after storing the update.
 */
class StoredUpdatesMiddleware extends AbstractTelegramMiddleware
{
    public function handler(): callable
    {
        return function (TelegramBotApi $api, Update $update, callable $next): void {
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
        };
    }
}

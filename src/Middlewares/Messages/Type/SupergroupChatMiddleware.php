<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares\Messages\Type;

use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Enums\ChatTypesEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Middleware that allows updates from supergroup chats.
 * It allows the next middleware or handler to be called only if the update is from a supergroup chat.
 */
final readonly class SupergroupChatMiddleware implements TelegramMiddlewareInterface
{
    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        $message = $update->message;

        if ($message && ChatTypesEnum::isSupergroup($message)) {
            $next();
        }
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares\Messages\Type;

use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Enums\ChatTypesEnum;
use Lowel\Telepath\Exceptions\ChatNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Middleware that excludes updates from supergroup chats.
 * It allows the next middleware or handler to be called only if the update is not from a supergroup chat.
 */
final class SupergroupExcludeChatMiddleware extends AbstractTelegramMiddleware
{
    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        try {
            $chat = Extrasense::chat();

            if (! ChatTypesEnum::isSupergroup($chat)) {
                $next();
            }
        } catch (ChatNotFoundInCurrentContextException|UpdateNotFoundInCurrentContextException $e) {
        }
    }
}

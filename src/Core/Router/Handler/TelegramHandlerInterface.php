<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for Telegram handlers.
 * Handlers are invoked when a specific pattern matches an incoming update.
 *
 * @method void __invoke(\Vjik\TelegramBot\Api\TelegramBotApi $api, Update $update)
 */
interface TelegramHandlerInterface {}

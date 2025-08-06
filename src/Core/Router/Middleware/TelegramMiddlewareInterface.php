<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Middleware;

use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for Telegram middleware.
 *
 * Middleware can be used to process updates before they reach the handler.
 * It can modify the update, perform logging, or handle specific conditions.
 *
 * @method void __invoke(\Vjik\TelegramBot\Api\TelegramBotApi $api, Update $update, callable $next)
 */
interface TelegramMiddlewareInterface {}

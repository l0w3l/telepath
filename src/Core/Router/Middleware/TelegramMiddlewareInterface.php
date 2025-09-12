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
 * @method mixed|void __invoke() - DI supported method
 */
interface TelegramMiddlewareInterface {}

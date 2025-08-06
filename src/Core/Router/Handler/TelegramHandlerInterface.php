<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for Telegram handlers.
 * Handlers are invoked when a specific pattern matches an incoming update.
 *
 * @phpstan-import-type MiddlewareHandler from TelegramRouterInterface
 *
 * @method void __invoke(\Vjik\TelegramBot\Api\TelegramBotApi $api, Update $update)
 * @method null|string pattern()
 * @method null|\Lowel\Telepath\Enums\UpdateTypeEnum type()
 * @method MiddlewareHandler[] middlewares()
 */
interface TelegramHandlerInterface {}

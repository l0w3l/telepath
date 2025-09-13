<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for Telegram handlers.
 * Handlers are invoked when a specific pattern matches an incoming update.
 *
 * @phpstan-import-type MiddlewareHandler from TelegramRouterInterface
 *
 * @method mixed|void __invoke() - DI supported method
 * @method null|string pattern() - text regex pattern for a handler (optional)
 * @method null|UpdateTypeEnum type() - handler type (optional)
 * @method MiddlewareHandler[] middlewares() - handler middlewares list (optional)
 *
 * @noinspection PhpUndefinedClassInspection
 */
interface TelegramHandlerInterface {}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for Telegram handlers.
 * Handlers are invoked when a specific pattern matches an incoming update.
 *
 *
 * @method mixed|void __invoke(TelegramBotApi $api, Update $update)
 * @method null|string pattern()
 * @method null|UpdateTypeEnum type()
 * @method MiddlewareHandler[] middlewares()
 *
 * @phpstan-import-type MiddlewareHandler from TelegramRouterInterface
 */
interface TelegramHandlerInterface {}

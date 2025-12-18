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
 */
interface TelegramHandlerInterface
{
    /**
     * Handle telegram typed event
     *
     * @return callable = DI supported telegram handler callback
     */
    public function handler(): callable;

    /**
     * Text pattern of telegram event
     */
    public function pattern(): ?string;

    /**
     * Type of telegram event
     */
    public function type(): ?UpdateTypeEnum;

    /**
     * Telegram event middlewares
     *
     * @return MiddlewareHandler[]
     */
    public function middlewares(): array;
}

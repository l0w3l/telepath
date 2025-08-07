<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;

/**
 * @template T of RouteContextInterface|TelegramRouterInterface
 *
 * @phpstan-import-type MiddlewareHandler from TelegramRouterInterface
 */
interface RouteContextInterface
{
    /**
     * Registers a middleware that will be applied to all handlers.
     * This allows you to add common logic that should run before or after the handler.
     *
     * @return T
     */
    public function middleware(array|string|callable $handler);

    /**
     * @return T
     */
    public function name(string $name);

    /**
     * @return T
     */
    public function pattern(string $pattern);

    /**
     * @return T
     */
    public function type(UpdateTypeEnum $updateTypeEnum);

    public function getParams(): RouteContextParams;
}

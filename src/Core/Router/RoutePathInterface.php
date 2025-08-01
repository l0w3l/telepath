<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Exceptions\Router\SubPathNotFoundException;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;

interface RoutePathInterface
{
    /**
     * @return TelegramHandlerInterface[]
     *
     * @throws TelegramHandlerNotFoundException
     */
    public function matchAll(?string $text): array;

    public function appendRouePath(RoutePathInterface $routePath): void;

    public function appendHandler(TelegramHandlerInterface $handler): void;

    /**
     * @throws SubPathNotFoundException
     */
    public function lastSubPath(): RoutePathInterface;
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core;

use Lowel\Telepath\Core\Drivers\TelegramAppDriverInterface;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;

final readonly class TelegramApp implements TelegramAppInterface
{
    public function __construct(
        public TelegramAppDriverInterface $driver,
        public TelegramBotApi $telegramBotApi,
        public TelegramRouterResolverInterface $routerResolver,
    ) {}

    public function start(): void
    {
        $this->driver->proceed($this->telegramBotApi, $this->routerResolver);
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core;

use Lowel\Telepath\Core\Drivers\TelegramAppDriverInterface;
use Lowel\Telepath\Core\GlobalAppContext\GlobalAppContextInitializerInterface;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Core\Traits\UpdateHandlerTrait;
use Vjik\TelegramBot\Api\TelegramBotApi;

final readonly class TelegramApp implements TelegramAppInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        public TelegramBotApi $telegramBotApi,
        public TelegramAppDriverInterface $driver,
        public TelegramRouterResolverInterface $routerResolver,
        public GlobalAppContextInitializerInterface $appContextInitializer
    ) {}

    public function start(): void
    {
        $updates = $this->driver->proceed($this->telegramBotApi);

        foreach ($updates as $update) {
            $this->initializeContext($update);

            $this->handleUpdate($update);

            $this->destroyContext();
        }
    }

    private function initializeContext(mixed $update): void
    {
        $this->appContextInitializer
            ->setUpdate($update)
            ->setDriver($this->driver);
    }

    private function destroyContext(): void
    {
        $this->appContextInitializer
            ->destroy();
    }
}

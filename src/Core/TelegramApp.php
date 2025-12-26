<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core;

use Lowel\Telepath\Core\Components\ComponentsBundle;
use Lowel\Telepath\Core\Drivers\TelegramAppDriverInterface;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Core\Traits\UpdateHandlerTrait;
use Phptg\BotApi\TelegramBotApi;
use Throwable;

final readonly class TelegramApp implements TelegramAppInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        public TelegramBotApi $telegramBotApi,
        public TelegramAppDriverInterface $driver,
        public TelegramRouterResolverInterface $routerResolver,
        public ComponentsBundle $componentsBundle
    ) {}

    public function start(): void
    {
        $this->componentsBundle->onCreated();

        $updates = $this->driver->proceed($this->telegramBotApi);

        foreach ($updates as $update) {
            $this->componentsBundle->onBefore($update);

            try {
                $this->handleUpdate($update);
            } catch (Throwable $e) {
                $this->componentsBundle->onError($update, $e);

                throw $e;
            }

            $this->componentsBundle->onAfter($update);
        }

        $this->componentsBundle->onDestroy();
    }
}

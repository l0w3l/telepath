<?php

declare(strict_types=1);

namespace Lowel\Telepath\Tests\Mock;

use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Components\ComponentsBundle;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Core\TelegramApp;
use Lowel\Telepath\Core\TelegramAppInterface;
use Lowel\Telepath\TelegramAppFactoryInterface;
use Phptg\BotApi\TelegramBotApi;

/**
 * Create mocking app
 */
class TelegramAppFactoryMock implements TelegramAppFactoryInterface
{
    public function __construct(
        private TestAppDriver $driver,
        private TelegramRouterResolverInterface $handlersCollection,
    ) {}

    public function longPooling(): TelegramAppInterface
    {
        return new TelegramApp(
            telegramBotApi: new TelegramBotApi(''),
            driver: $this->driver,
            routerResolver: $this->handlersCollection,
            componentsBundle: App::make(ComponentsBundle::class)
        );
    }

    public function webhook(): TelegramAppInterface
    {
        return new TelegramApp(
            telegramBotApi: new TelegramBotApi(''),
            driver: $this->driver,
            routerResolver: $this->handlersCollection,
            componentsBundle: App::make(ComponentsBundle::class)
        );
    }
}

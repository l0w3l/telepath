<?php

declare(strict_types=1);

namespace Lowel\Telepath;

use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Components\ComponentsBundle;
use Lowel\Telepath\Core\Drivers\LongPoolingDriverTelegram;
use Lowel\Telepath\Core\Drivers\WebhookDriverTelegram;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Core\TelegramApp;
use Lowel\Telepath\Core\TelegramAppInterface;
use Lowel\Telepath\Facades\Extrasense;
use Phptg\BotApi\TelegramBotApi;

final readonly class TelegramAppFactory implements TelegramAppFactoryInterface
{
    public function __construct(
        private TelegramBotApi $telegramBotApi,
        private TelegramRouterResolverInterface $handlersCollection,
    ) {}

    public function longPooling(): TelegramAppInterface
    {
        $profile = Extrasense::profile();

        return new TelegramApp(
            telegramBotApi: $this->telegramBotApi,
            driver: new LongPoolingDriverTelegram(
                timeout: $profile->timeout,
                limit: $profile->limit,
                allowedUpdates: $profile->allowedUpdates,
            ),
            routerResolver: $this->handlersCollection,
            componentsBundle: App::make(ComponentsBundle::class)
        );
    }

    public function webhook(string $json): TelegramAppInterface
    {
        return new TelegramApp(
            telegramBotApi: $this->telegramBotApi,
            driver: new WebhookDriverTelegram($json),
            routerResolver: $this->handlersCollection,
            componentsBundle: App::make(ComponentsBundle::class)
        );
    }
}

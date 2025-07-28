<?php

declare(strict_types=1);

namespace Lowel\Telepath;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Drivers\LongPoolingDriverTelegram;
use Lowel\Telepath\Core\Drivers\WebhookDriverTelegram;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Lowel\Telepath\Core\TelegramApp;
use Lowel\Telepath\Core\TelegramAppInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;

final readonly class TelegramAppFactory implements TelegramAppFactoryInterface
{
    public function __construct(
        private TelegramBotApi $telegramBotApi,
        private TelegramHandlerCollectionInterface $handlersCollection,
    ) {}

    public function longPooling(): TelegramAppInterface
    {
        $profile = config('telepath.profiles')[config('telepath.profile', 'default')];

        return new TelegramApp(
            driver: new LongPoolingDriverTelegram(
                timeout: $profile['timeout'] ?? 30,
                limit: $profile['limit'] ?? 100,
                allowedUpdates: UpdateTypeEnum::toArray($profile['allowed_updates']),
            ),
            telegramBotApi: $this->telegramBotApi,
            handlersCollection: $this->handlersCollection,
        );
    }

    public function webhook(): TelegramAppInterface
    {
        $request = App::make(Request::class);

        return new TelegramApp(
            driver: new WebhookDriverTelegram($request),
            telegramBotApi: $this->telegramBotApi,
            handlersCollection: $this->handlersCollection,
        );
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Generator;
use Illuminate\Http\Request;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class WebhookDriverTelegram implements TelegramAppDriverInterface
{
    public function __construct(
        private Request $request
    ) {}

    public function proceed(TelegramBotApi $telegramBotApi): Generator
    {
        $content = $this->request->getContent();

        if (json_validate($content)) {
            yield Update::fromJson($content);
        }
    }
}

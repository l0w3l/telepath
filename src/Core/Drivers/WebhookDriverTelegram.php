<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Generator;
use Lowel\Telepath\Exceptions\TelegramAppException;
use Phptg\BotApi\ParseResult\TelegramParseResultException;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;

final readonly class WebhookDriverTelegram implements TelegramAppDriverInterface
{
    public function __construct(
        private string $json
    ) {}

    public function proceed(TelegramBotApi $telegramBotApi): Generator
    {
        try {
            yield Update::fromJson($this->json, logger());
        } catch (TelegramParseResultException $e) {
            throw new TelegramAppException($this->json, previous: $e);
        }
    }
}

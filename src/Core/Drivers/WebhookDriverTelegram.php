<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Generator;
use Lowel\Telepath\Exceptions\TelegramAppException;
use Phptg\BotApi\ParseResult\TelegramParseResultException;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;
use Psr\Http\Message\ServerRequestInterface;

final readonly class WebhookDriverTelegram implements TelegramAppDriverInterface
{
    public function __construct(
        private ServerRequestInterface $request
    ) {}

    public function proceed(TelegramBotApi $telegramBotApi): Generator
    {
        try {
            yield Update::fromServerRequest($this->request, logger());
        } catch (TelegramParseResultException $e) {
            throw new TelegramAppException($this->request, previous: $e);
        }
    }
}

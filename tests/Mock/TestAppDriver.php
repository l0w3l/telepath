<?php

declare(strict_types=1);

namespace Lowel\Telepath\Tests\Mock;

use Generator;
use Lowel\Telepath\Core\Drivers\TelegramAppDriverInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

class TestAppDriver implements TelegramAppDriverInterface
{
    /**
     * @param  Update  $updates
     */
    public function __construct(
        public array $updates
    ) {}

    public function proceed(TelegramBotApi $telegramBotApi): Generator
    {
        foreach ($this->updates as $update) {
            yield $update;
        }
    }
}

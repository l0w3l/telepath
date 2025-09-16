<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Generator;
use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Exceptions\TelegramAppException;
use Vjik\TelegramBot\Api\FailResult;
use Vjik\TelegramBot\Api\TelegramBotApi;

final class LongPoolingDriverTelegram implements TelegramAppDriverInterface
{
    private ?int $lastUpdateId = null;

    /**
     * LongPoolingDriver constructor.
     *
     * @param  int  $timeout  The timeout in seconds for long polling.
     * @param  int  $limit  The maximum number of updates to retrieve.
     * @param  string[]  $allowedUpdates  An array of update types to receive.
     */
    public function __construct(
        private readonly int $timeout = 30,
        private readonly int $limit = 100,
        private readonly array $allowedUpdates = [],
    ) {}

    public function proceed(TelegramBotApi $telegramBotApi): Generator
    {
        $updates = $telegramBotApi->getUpdates($this->lastUpdateId, $this->limit, $this->timeout, $this->allowedUpdates);

        if ($updates instanceof FailResult) {
            Log::error('Failed to retrieve updates', ['update' => $updates]);

            throw new TelegramAppException($updates);
        }

        foreach ($updates as $update) {
            $this->lastUpdateId = $update->updateId + 1;

            yield $update;
        }
    }
}

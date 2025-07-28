<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Lowel\Telepath\Commands\Exceptions\TelegramAppException;
use Lowel\Telepath\Core\Drivers\Traits\UpdateHandlerTrait;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Vjik\TelegramBot\Api\FailResult;
use Vjik\TelegramBot\Api\TelegramBotApi;

final class LongPoolingDriverTelegram implements TelegramAppDriverInterface
{
    use UpdateHandlerTrait;

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

    public function proceed(TelegramBotApi $telegramBotApi, TelegramHandlerCollectionInterface $handlersCollection): void
    {
        $updates = $telegramBotApi->getUpdates($this->lastUpdateId, $this->limit, $this->timeout, $this->allowedUpdates);

        if ($updates instanceof FailResult) {
            throw new TelegramAppException('Failed to retrieve updates: '.$updates->response->body);
        }

        foreach ($updates as $update) {
            $this->processUpdate($update, $telegramBotApi, $handlersCollection);

            $this->lastUpdateId = $update->updateId + 1;
        }
    }
}

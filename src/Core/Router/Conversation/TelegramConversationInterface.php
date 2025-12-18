<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation;

use Lowel\Telepath\Core\Router\Conversation\Promise\TelegramPromiseInterface;

/**
 * Interface for Telegram conversation that supports asynchronous handling.
 */
interface TelegramConversationInterface
{
    /**
     * Registers a promise that will be resolved when the conversation is completed.
     *
     * @return TelegramPromiseInterface[]|array<array{resolve?: callable, reject?: callable|null, ttl?: int}>
     */
    public function promises(): array;
}

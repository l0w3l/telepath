<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation;

/**
 * Interface for Telegram conversation that supports asynchronous handling.
 */
interface TelegramConversationInterface
{
    /**
     * Registers a promise that will be resolved when the conversation is completed.
     *
     * @return TelegramPromiseInterface[]
     */
    public function promises(): array;
}

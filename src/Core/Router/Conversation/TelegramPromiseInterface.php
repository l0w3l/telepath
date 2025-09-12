<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation;

/**
 * Interface for Telegram conversations.
 *
 * @method int ttl() Returns the time-to-live for the conversation.
 * @method mixed|void resolve() DI supported methods. Resolves the conversation with the given API, update, and shared data.
 * @method mixed|void reject() DI supported method. Rejects the conversation with the given API, update, error, and shared data.
 */
interface TelegramPromiseInterface {}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation;

use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for Telegram conversations.
 *
 * @method int ttl() Returns the time-to-live for the conversation.
 * @method mixed|void resolve(TelegramBotApi $api, Update $update, mixed $shared) Resolves the conversation with the given API, update, and shared data.
 * @method mixed|void reject(TelegramBotApi $api, Update $update, Throwable $error, mixed $shared) Rejects the conversation with the given API, update, error, and shared data.
 */
interface TelegramPromiseInterface {}

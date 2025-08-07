<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares\Messages;

use Closure;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Middleware to exclude messages from users.
 * It allows processing messages that are not sent by users (e.g., from channels or bots).
 *
 * @see OnlyForUsersMiddleware
 */
final readonly class NotForUsersMiddleware implements TelegramMiddlewareInterface
{
    /**
     * @param  Closure():int[]|int[]|null  $excludeUsers  List of user IDs to exclude from processing.
     */
    public function __construct(
        private null|array|Closure $excludeUsers = null,
    ) {}

    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        $message = $update->message;

        if ($message->senderChat === null && ! in_array($message->from?->id, $this->getExcludedIds())) {
            $next();
        }
    }

    /**
     * @return int[] List of user IDs to exclude from processing
     */
    public function getExcludedIds(): array
    {
        if ($this->excludeUsers instanceof Closure) {
            return $this->excludeUsers->call($this);
        } else {
            $profile = config('telepath.profiles')[config('telepath.profile')];

            return $this->excludeUsers ?? $profile['banned'] ?? [];
        }
    }
}

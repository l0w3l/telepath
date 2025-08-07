<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares\Messages;

use Closure;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Middleware to allow processing messages only from specific users.
 * If the message is from a user not in the allowed list, it will not be processed.
 */
final readonly class OnlyForUsersMiddleware implements TelegramMiddlewareInterface
{
    /**
     * @param  Closure():int[]|int[]|null  $allowedUserIds  List of user IDs to allow processing for.
     */
    public function __construct(
        private null|Closure|array $allowedUserIds,
    ) {}

    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        $message = $update->message;

        if ($message->senderChat === null && in_array($message->from?->id, $this->getAllowedIds())) {
            $next();
        }
    }

    /**
     * @return int[] List of user IDs to exclude from processing
     */
    public function getAllowedIds(): array
    {
        if ($this->allowedUserIds instanceof Closure) {
            return $this->allowedUserIds->call($this);
        } else {
            $profile = config('telepath.profiles')[config('telepath.profile')];

            return $this->allowedUserIds ?? $profile['banned'] ?? [];
        }
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares\Messages;

use Closure;
use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UserNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Middlewares\Traits\AllowedExcludeIdsTrait;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Middleware to exclude messages from users.
 * It allows processing messages that are not sent by users (e.g., from channels or bots).
 *
 * @see OnlyForUsersMiddleware
 */
final class NotForUsersMiddleware implements TelegramMiddlewareInterface
{
    use AllowedExcludeIdsTrait;

    /**
     * @param  Closure():int[]|int[]|null  $excludeUsers  List of user IDs to exclude from processing.
     */
    public function __construct(
        null|Closure|array $excludeUsers = null
    ) {
        $this->allowedUserIds = null;
        $this->excludeUserIds = $excludeUsers ?? config('telepath.profiles')[config('telepath.profile')]['banned'];
    }

    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        try {
            $user = Extrasense::user();

            if (! in_array($user->id, $this->getExcludeIds())) {
                $next();
            } else {
                Log::debug("User {$user->username} ({$user->id}) was rejected", ['update' => $update]);
            }
        } catch (UserNotFoundInCurrentContextException|UpdateNotFoundInCurrentContextException $e) {
            // not valid type
        }
    }
}

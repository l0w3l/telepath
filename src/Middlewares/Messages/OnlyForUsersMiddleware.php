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
 * Middleware to allow processing messages only from specific users.
 * If the message is from a user not in the allowed list, it will not be processed.
 */
final class OnlyForUsersMiddleware implements TelegramMiddlewareInterface
{
    use AllowedExcludeIdsTrait;

    /**
     * @param  Closure():int[]|int[]|null  $allowedUserIds  List of user IDs to allow processing for.
     */
    public function __construct(
        null|Closure|array $allowedUserIds = null
    ) {
        $this->allowedUserIds = $allowedUserIds ?? config('telepath.profiles')[config('telepath.profile')]['admins'];
        $this->excludeUserIds = null;
    }

    public function __invoke(TelegramBotApi $api, Update $update, callable $next): void
    {
        try {
            $user = Extrasense::user();

            if (in_array($user->id, $this->getAllowedIds())) {
                $next();
            } else {
                Log::debug("User {$user->username} ({$user->id}) was rejected", ['update' => $update]);
            }
        } catch (UserNotFoundInCurrentContextException|UpdateNotFoundInCurrentContextException $e) {
            // not valid type
        }
    }
}

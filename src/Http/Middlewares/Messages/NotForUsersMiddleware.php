<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\Messages;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Facades\Extrasense;

/**
 * Middleware to exclude messages from users.
 * It allows processing messages that are not sent by users (e.g., from channels or bots).
 *
 * @see OnlyForUsersMiddleware
 */
final class NotForUsersMiddleware
{
    public function handler(Request $request, Closure $next): Response
    {
        $user = Extrasense::user();

        if (in_array($user->id, Extrasense::profile()->blacklist)) {
            Log::debug("User {$user->username} ({$user->id}) was rejected", ['update' => Extrasense::update()]);
        } else {
            return $next($request);
        }

        return response(status: 200);
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\Messages;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Facades\Extrasense;

/**
 * Middleware to allow processing messages only from specific users.
 * If the message is from a user not in the allowed list, it will not be processed.
 */
final class OnlyForUsersMiddleware
{
    public function handler(Request $request, Closure $next): Response
    {
        $user = Extrasense::user();

        if (in_array($user->id, Extrasense::profile()->whitelist)) {
            return $next($request);
        } else {
            Log::debug("User {$user->username} ({$user->id}) was rejected", ['update' => Extrasense::update()]);
        }

        return response(status: 200);
    }
}

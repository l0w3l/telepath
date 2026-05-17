<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\Messages\Type;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lowel\Telepath\Enums\ChatTypesEnum;
use Lowel\Telepath\Facades\Extrasense;

/**
 * Middleware that allows updates from supergroup chats.
 * It allows the next middleware or handler to be called only if the update is from a supergroup chat.
 */
final class PrivateExcludeChatMiddleware
{
    public function handler(Request $request, \Closure $next): Response
    {
        $chat = Extrasense::chat();

        if (! ChatTypesEnum::isPrivate($chat)) {
            return $next($request);
        } else {
            return response(status: 200);
        }
    }
}

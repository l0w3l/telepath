<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\Authorization;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramOriginMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('telepath.get_updates')) {
            return $next($request);
        }

        $secret = config('telepath.hook.secret');

        if (empty($secret)) {
            return $next($request);
        }

        $telegramBotApiSecretToken = $request->header('Telegram-Bot-Api-Secret-Token') ?? '';

        if (hash_equals($telegramBotApiSecretToken, $secret)) {
            return $next($request);
        }

        return response(status: Response::HTTP_FORBIDDEN);
    }
}

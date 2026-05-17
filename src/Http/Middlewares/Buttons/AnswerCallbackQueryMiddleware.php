<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\Buttons;

use Closure;
use Illuminate\Http\Request;
use Lowel\Telepath\Facades\SpiritBox;

class AnswerCallbackQueryMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        SpiritBox::answerCallbackQuery();

        return $response ?? response(status: 200);
    }
}

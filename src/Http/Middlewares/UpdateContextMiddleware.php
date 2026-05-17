<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Lowel\Telepath\Components\Context\Context;
use Phptg\BotApi\Type\Update\Update;

class UpdateContextMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('telepath.get_updates')) {
            return $next($request);
        }

        $context = app()->make(Context::class);

        $update = $request->attributes->get('telepath.update')
            ?? Update::fromJson($request->getContent());

        $context->onBefore($update);

        try {
            return $next($request);
        } finally {
            $context->onAfter($update);
        }
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Exceptions\Router\SubPathNotFoundException;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;

class RoutePath implements RoutePathInterface
{
    /**
     * @var RoutePathInterface[]
     */
    private array $subPaths = [];

    /**
     * @var TelegramHandlerInterface[]
     */
    private array $handlers = [];

    public function matchAll(?string $text = null): array
    {
        $matches = [];

        foreach ($this->handlers as $handler) {
            if ($handler->pattern() === null) {
                $matches[] = $handler;
            } else {
                $matchResult = Str::match($handler->pattern(), $text);

                if ($matchResult !== '') {
                    $matches[] = $handler;
                }
            }
        }

        foreach ($this->subPaths as $subPath) {
            try {
                $matches = $subPath->matchAll($text);
            } catch (TelegramHandlerNotFoundException $e) {
                continue;
            }
        }

        if (! empty($matches)) {
            return $matches;
        } else {
            throw new TelegramHandlerNotFoundException("Handler not found by text `$text`");
        }
    }

    public function appendRouePath(RoutePathInterface $routePath): void
    {
        $this->subPaths[] = $routePath;
    }

    public function appendHandler(TelegramHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function lastSubPath(): RoutePathInterface
    {
        return end($this->subPaths) ?: throw new SubPathNotFoundException;
    }
}

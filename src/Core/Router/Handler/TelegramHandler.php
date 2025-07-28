<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Illuminate\Support\Str;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class TelegramHandler implements TelegramHandlerInterface
{
    public ?string $pattern;

    public mixed $callable;

    /**
     * @param  callable(TelegramBotApi, Update): mixed  $callback
     */
    public function __construct(
        callable $callback,
        ?string $pattern = null,
    ) {
        if ($pattern === '' || is_null($pattern)) {
            $this->pattern = null;
        } elseif (Str::startsWith($pattern, '/') && Str::endsWith($pattern, '/')) {
            $this->pattern = $pattern;
        } else {
            $pattern = Str::replaceFirst('/', '\\/', $pattern);

            $this->pattern = "/^($pattern)$/";
        }

        $this->callable = $callback;
    }

    public function pattern(): ?string
    {
        return $this->pattern;
    }

    public function __invoke(TelegramBotApi $telegram, Update $update): mixed
    {
        return call_user_func($this->callable, $telegram, $update);
    }
}

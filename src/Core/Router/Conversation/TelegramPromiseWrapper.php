<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Lowel\Telepath\Core\Traits\InvokeAbleTrait;
use ReflectionException;
use RuntimeException;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Class TelegramPromiseWrapper
 *
 * This class wraps a promise for conversation-style handling of Telegram updates.
 * It allows you to define a resolve and reject handler, and provides a TTL for the promise.
 */
readonly class TelegramPromiseWrapper implements TelegramPromiseInterface
{
    use InvokeAbleTrait;

    private int $ttl;

    /**
     * Constructor for TelegramPromiseWrapper.
     *
     * @param  Closure(TelegramBotApi, Update): (mixed|void)  $resolve  The function to call when the promise is resolved.
     * @param  null|Closure(TelegramBotApi, Update, Throwable): (mixed|void)  $reject  The function to call when the promise is rejected. If null, an exception will be thrown on rejection.
     * @param  int|null  $ttl  The time-to-live for the promise in seconds. Defaults to 60 seconds if not provided.
     */
    public function __construct(
        private Closure $resolve,
        private ?Closure $reject = null,
        ?int $ttl = null
    ) {
        $this->ttl = $ttl ?? config('telepath.conversation.ttl', 60);
    }

    /**
     * Resolve the promise with the provided API, update, and shared data.
     *
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function resolve(TelegramBotApi $api, Update $update, mixed $shared)
    {
        return $this->invokeStaticClassWithArgs(
            $this->resolve,
            compact('api', 'update', 'shared'),
        );
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function reject(TelegramBotApi $api, Update $update, Throwable $error, mixed $shared)
    {
        if ($this->reject !== null) {
            return $this->invokeStaticClassWithArgs(
                $this->reject,
                compact('api', 'update', 'shared', 'error'),
            );
        } else {
            throw new RuntimeException(
                'Promise rejected without a reject handler. '.
                'Please provide a reject handler or handle the exception in your code.',
                0,
                $error
            );
        }
    }

    public function ttl(): int
    {
        return $this->ttl;
    }
}

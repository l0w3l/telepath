<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context\Executor;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Lowel\Telepath\Core\Router\Context\Executor\Traits\InvokeAbleTrait;
use Lowel\Telepath\Core\Router\Context\RouteContextParams;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use ReflectionException;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class RouteRouteExecutor implements RouteExecutorInterface
{
    use InvokeAbleTrait;

    public function __construct(
        public RouteContextParams $params
    ) {}

    public function affect(RouteContextParams $params): self
    {
        $this->params
            ->unshiftMiddleware($params->getMiddlewares())
            ->setName($params->getName())
            ->setPattern($params->getPattern())
            ->setUpdateTypeEnum($params->getUpdateTypeEnum());

        return $this;
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function proceed(TelegramBotApi $telegramBotApi, Update $update): void
    {
        $callable = fn () => $this->invokeStaticClassWithArgs($this->params->getHandler(), [
            'telegramBotApi' => $telegramBotApi,
            'update' => $update,
        ]);

        foreach ($this->params->getMiddlewaresReverse() as $middleware) {
            $callable = fn () => $this->invokeStaticClassWithArgs(
                $middleware,
                [
                    'telegramBotApi' => $telegramBotApi,
                    'update' => $update,
                    'callable' => $callable,
                    'callback' => $callable,
                ]
            );
        }

        $callable();
    }

    public function match(UpdateTypeEnum $updateTypeEnum, ?string $text = null): bool
    {
        if ($this->params->hasUpdateTypeEnum()) {
            if ($this->params->hasPattern()) {
                return $this->params->getUpdateTypeEnum() === $updateTypeEnum && Str::match(Str::deduplicate("/{$this->params->getPattern()}/", '/'), $text ?? '');
            } else {
                return $this->params->getUpdateTypeEnum() === $updateTypeEnum;
            }
        } else {
            if ($this->params->hasPattern()) {
                return (bool) Str::match($this->params->getPattern(), $text ?? '');
            } else {
                return true;
            }
        }
    }
}

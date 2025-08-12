<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context\Executor;

use Illuminate\Support\Str;
use Lowel\Telepath\Core\Router\Context\RouteContextParams;
use Lowel\Telepath\Core\Router\Conversation\ConversationStorageFactory;
use Lowel\Telepath\Core\Traits\InvokeAbleTrait;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Psr\SimpleCache\InvalidArgumentException;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class RouteExecutor implements RouteExecutorInterface
{
    use InvokeAbleTrait;

    public function __construct(
        public RouteContextParams $params,
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
     * @throws InvalidArgumentException
     */
    public function proceed(TelegramBotApi $api, Update $update): void
    {
        $proceedResult = $this->resolve($api, $update);

        if ($update->message && $this->params->hasConversation()) {
            $this->initializeConversation($update, $proceedResult);
        }
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

    protected function resolve(TelegramBotApi $api, Update $update)
    {
        $callable = fn () => $this->invokeStaticClassWithArgs($this->params->getHandler(), [
            'api' => $api,
            'telegramBotApi' => $api,
            'update' => $update,
        ]);

        foreach ($this->params->getMiddlewaresReverse() as $middleware) {
            $callable = fn () => $this->invokeStaticClassWithArgs(
                $middleware,
                [
                    'telegramBotApi' => $api,
                    'api' => $api,
                    'update' => $update,
                    'callable' => $callable,
                    'callback' => $callable,
                    'next' => $callable,
                ]
            );
        }

        return $callable();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function initializeConversation(Update $update, mixed $proceedResult): void
    {
        $conversation = $this->params->getConversation();
        $conversationStorageFactory = new ConversationStorageFactory;

        $conversationStorageFactory->create($update)
            ->initialize($conversation)
            ->storeShared($proceedResult, $conversation[0]);

    }
}

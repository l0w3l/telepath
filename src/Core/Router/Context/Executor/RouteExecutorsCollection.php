<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context\Executor;

use Lowel\Telepath\Core\Router\Conversation\Storage\ConversationPositionData;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class RouteExecutorsCollection
{
    public function __construct(
        /**
         * @var RouteExecutorInterface[]
         */
        private array $handlers,
        /**
         * @var RouteExecutorInterface[]
         */
        private array $fallbacks,
    ) {}

    public function resolveConversation(ConversationPositionData $conversationPositionData): RouteExecutorInterface
    {
        $executors = $this->resolve($conversationPositionData->trigger);

        foreach ($executors as $executor) {
            if ($executor->hasConversation()) {
                return $executor;
            }
        }

        return throw new \RuntimeException('Conversation was not found by given update.');
    }

    public function resolve(Update $update): array
    {
        $types = UpdateTypeEnum::resolve($update);
        $executors = [];

        foreach ($types as $type) {
            $text = UpdateTypeEnum::extractText($update, $type);

            foreach ($this->handlers as $executor) {
                if ($executor->match($type, $text)) {
                    $executors[] = $executor;
                }
            }
        }

        if (empty($executors)) {
            $executors = $this->fallbacks;
        }

        return $executors;
    }

    public function getAllUpdateTypes(): array
    {
        $types = [];

        foreach ($this->handlers as $executor) {
            $type = $executor->type()->value;

            if (!in_array($type, $types)) {
                $types[] = $type;
            }
        }

        return $types;
    }
}

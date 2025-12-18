<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation\Storage;

use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class ConversationPositionData
{
    public function __construct(
        public Update $trigger,
        public int $position,
        public int $end,
        public mixed $shared,
    ) {}

    /**
     * @param  array{trigger: Update, position: int, end: int, shared: mixed}  $array
     */
    public static function fromArray(array $array): self
    {
        return new self($array['trigger'], $array['position'], $array['end'], $array['shared']);
    }
}

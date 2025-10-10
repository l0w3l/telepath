<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Vjik\TelegramBot\Api\Type\ForceReply;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;
use Vjik\TelegramBot\Api\Type\ReplyKeyboardMarkup;
use Vjik\TelegramBot\Api\Type\ReplyKeyboardRemove;

interface KeyboardBuilderInterface
{
    public function build(array $args = []): InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply;

    /**
     * @param  callable(ButtonInterface): bool  $comparator
     */
    public function filter(callable $comparator): self;

    public function each(callable $callable): self;

    public function map(callable $callback): self;

    public function copy(array $keyboardMarkup = []): self;

    /**
     * return keyboard markup
     *
     * @return ButtonInterface[][]
     */
    public function toArray(): array;
}

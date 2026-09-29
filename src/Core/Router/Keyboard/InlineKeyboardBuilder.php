<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Phptg\BotApi\Type\InlineKeyboardMarkup;

final class InlineKeyboardBuilder extends AbstractKeyboardBuilder
{
    public function __construct(public ?bool $forceReply = null) {}

    public static function create(?bool $forceReply = null): self
    {
        return new self($forceReply);
    }

    public function build(): InlineKeyboardMarkup
    {
        $buttons = array_map(fn (array $column) => array_map(fn (ButtonInterface $button) => $button->toButton(), $column), $this->keyboardMarkup);

        return new InlineKeyboardMarkup($buttons, $this->forceReply);
    }

    public function copy(array $keyboardMarkup = []): self
    {
        return (new self($this->forceReply))->markup($keyboardMarkup);
    }
}

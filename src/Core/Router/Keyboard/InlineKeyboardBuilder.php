<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;

final class InlineKeyboardBuilder extends AbstractKeyboardBuilder
{
    public function build(array $args = []): InlineKeyboardMarkup
    {
        $buttons = array_map(fn (array $column) => array_map(fn (ButtonInterface $button) => $button->toButton($args), $column), $this->keyboardMarkup);

        return new InlineKeyboardMarkup($buttons);
    }

    public function copy(array $keyboardMarkup = []): self
    {
        return (new self)->markup($keyboardMarkup);
    }
}

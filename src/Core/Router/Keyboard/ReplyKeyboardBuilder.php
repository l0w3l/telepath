<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Phptg\BotApi\Type\ReplyKeyboardMarkup;
use Phptg\BotApi\Type\ReplyKeyboardRemove;

class ReplyKeyboardBuilder extends AbstractKeyboardBuilder
{
    public function __construct(
        public ?bool $isPersistent = null,
        public ?bool $resizeKeyboard = null,
        public ?bool $oneTimeKeyboard = null,
        public ?string $inputFieldPlaceholder = null,
        public ?bool $selective = null,
    ) {}

    public static function create(
        ?bool $isPersistent = null,
        ?bool $resizeKeyboard = null,
        ?bool $oneTimeKeyboard = null,
        ?string $inputFieldPlaceholder = null,
        ?bool $selective = null,
    ): self {
        return new self(
            isPersistent: $isPersistent,
            resizeKeyboard: $resizeKeyboard,
            oneTimeKeyboard: $oneTimeKeyboard,
            inputFieldPlaceholder: $inputFieldPlaceholder,
            selective: $selective
        );
    }

    public function build(): ReplyKeyboardMarkup
    {
        $buttons = array_map(fn (array $column) => array_map(fn (ButtonInterface $button) => $button->toButton(), $column), $this->keyboardMarkup);

        return new ReplyKeyboardMarkup($buttons);
    }

    public function remove(): ReplyKeyboardRemove
    {
        return new ReplyKeyboardRemove;
    }

    public function copy(array $keyboardMarkup = []): self
    {
        return (new self($this->isPersistent, $this->resizeKeyboard, $this->oneTimeKeyboard, $this->inputFieldPlaceholder, $this->selective))->markup($this->keyboardMarkup);
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use RuntimeException;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;

final class InlineKeyboardBuilder implements KeyboardBuilderInterface
{
    private array $keyboardMarkup = [[]];

    public function row(ButtonInterface ...$button): self
    {
        $lastButtonMatrixElementIndex = array_key_last($this->keyboardMarkup) - 1;

        if (array_key_exists($lastButtonMatrixElementIndex, $this->keyboardMarkup) && is_array($this->keyboardMarkup[$lastButtonMatrixElementIndex])) {
            $this->keyboardMarkup[$lastButtonMatrixElementIndex] = array_merge($this->keyboardMarkup[$lastButtonMatrixElementIndex], $button);
        } else {
            $this->keyboardMarkup[] = $button;
        }

        return $this;
    }

    public function column(ButtonInterface ...$button): self
    {
        $this->keyboardMarkup = array_merge($this->keyboardMarkup, array_map(fn ($x) => [$x], $button));

        return $this;
    }

    public function markup(array $markup): self
    {
        foreach ($markup as $column) {
            if (is_array($column)) {
                foreach ($column as $row) {
                    if (! ($row instanceof ButtonInterface)) {
                        throw new RuntimeException('Markup elements should be passed as '.ButtonInterface::class.' instance');
                    }
                }
            }
            if (! ($column instanceof ButtonInterface)) {
                throw new RuntimeException('Markup elements should be passed as '.ButtonInterface::class.' instance');
            }
        }

        $this->keyboardMarkup = array_merge($this->keyboardMarkup, $markup);

        return $this;
    }

    public function build(array $args = []): InlineKeyboardMarkup
    {
        $buttons = array_map(fn (array $column) => array_map(fn (ButtonInterface $button) => $button->toButton($args), $column), $this->keyboardMarkup);

        return new InlineKeyboardMarkup($buttons);
    }

    public function toArray(): array
    {
        return $this->keyboardMarkup;
    }
}

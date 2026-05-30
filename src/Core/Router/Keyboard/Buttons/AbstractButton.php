<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons;

use Illuminate\Support\Facades\App;
use Lowel\Telepath\Enums\ButtonStyleEnum;

abstract class AbstractButton implements ButtonInterface
{
    protected ?string $style = null;

    protected ?string $iconCustomEmojiId = null;

    protected string $text = 'example';

    public static function make(array $args = []): static
    {
        return App::make(static::class, $args);
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(ButtonStyleEnum $style): static
    {
        $this->style = $style->value;

        return $this;
    }

    public function setStylePrimary(bool $condition = true): static
    {
        if ($condition) {
            $this->setStyle(ButtonStyleEnum::PRIMARY);
        }

        return $this;
    }

    public function setStyleSuccess(bool $condition = true): static
    {
        if ($condition) {
            $this->setStyle(ButtonStyleEnum::SUCCESS);
        }

        return $this;
    }

    public function setStyleDanger(bool $condition = true): static
    {
        if ($condition) {
            $this->setStyle(ButtonStyleEnum::DANGER);
        }

        return $this;
    }

    public function resetStyle(): static
    {
        $this->style = null;

        return $this;
    }

    public function getText(): string
    {
        return __($this->text);
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function setCustomEmojiId(int|string $customEmojiId): static
    {
        $this->iconCustomEmojiId = (string) $customEmojiId;

        return $this;
    }

    public function getIconCustomEmojiId(): ?string
    {
        return $this->iconCustomEmojiId;
    }
}

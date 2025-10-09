<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use RuntimeException;

abstract class AbstractKeyboardBuilder implements KeyboardBuilderInterface
{
    /**
     * @var ButtonInterface[][]
     */
    protected array $keyboardMarkup = [[]];

    public function row(ButtonInterface ...$button): static
    {
        $lastButtonMatrixElementIndex = array_key_last($this->keyboardMarkup) - 1;

        if (array_key_exists($lastButtonMatrixElementIndex, $this->keyboardMarkup) && ! empty($this->keyboardMarkup[$lastButtonMatrixElementIndex])) {
            $this->keyboardMarkup[$lastButtonMatrixElementIndex] = array_merge($this->keyboardMarkup[$lastButtonMatrixElementIndex], $button);
        } else {
            $this->keyboardMarkup[] = $button;
        }

        return $this;
    }

    public function column(ButtonInterface ...$button): static
    {
        $this->keyboardMarkup = array_merge($this->keyboardMarkup, array_map(fn ($x) => [$x], $button));

        return $this;
    }

    public function markup(array $markup): static
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

    public function filter(callable $comparator): KeyboardBuilderInterface
    {
        $newMarkup = [];

        foreach ($this->toArray() as $column) {
            $newColumn = [];
            foreach ($column as $button) {
                if ($comparator($button)) {
                    $newColumn[] = $button;
                }
            }

            if (! empty($newColumn)) {
                $newMarkup[] = $newColumn;
            }
        }

        return $this->copy($newMarkup);
    }

    /**
     * @template T of ButtonInterface
     *
     * @param  callable(T): T  $callable
     */
    public function each(callable $callable): KeyboardBuilderInterface
    {
        $newMarkup = [];

        foreach ($this->toArray() as $column) {
            $newColumn = [];
            foreach ($column as $button) {
                $callable($button);

                $newColumn[] = $button;
            }

            $newMarkup[] = $newColumn;
        }

        return $this->copy($newMarkup);
    }

    public function map(callable $callback): KeyboardBuilderInterface
    {
        $newMarkup = [];

        foreach ($this->toArray() as $column) {
            $newColumn = [];
            foreach ($column as $button) {
                $newColumn[] = $callback($button);
            }

            $newMarkup[] = $newColumn;
        }

        return $this->copy($newMarkup);
    }

    /**
     * @return ButtonInterface[][]
     */
    public function toArray(): array
    {
        return $this->keyboardMarkup;
    }
}

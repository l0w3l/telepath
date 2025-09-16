<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\KeyboardInterface;

interface KeyboardsWatcherInterface
{
    /**
     * @param  class-string<KeyboardInterface>  ...$keyboards
     */
    public function watch(string ...$keyboards): self;
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\KeyboardFactoryInterface;

interface KeyboardsWatcherInterface
{
    /**
     * @param  class-string<KeyboardFactoryInterface>  ...$keyboards
     */
    public function watch(string ...$keyboards): self;
}

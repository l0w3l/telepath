<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards;

interface KeyboardFactoryInterface
{
    public function make(): KeyboardBuilderInterface;
}

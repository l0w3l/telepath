<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard;

interface KeyboardFactoryInterface
{
    public function make(): KeyboardBuilderInterface;
}

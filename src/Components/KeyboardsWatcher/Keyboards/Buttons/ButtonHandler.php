<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons;

use Closure;
use Lowel\Telepath\Enums\UpdateTypeEnum;

final readonly class ButtonHandler
{
    public function __construct(
        public Closure $callback,
        public string $pattern,
        public UpdateTypeEnum $updateType,
    ) {}
}

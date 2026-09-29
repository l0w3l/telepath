<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\AbstractButton;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Phptg\BotApi\Type\DisabledButton;

abstract class AbstractInlineButton extends AbstractButton implements ButtonInterface
{
    protected bool $disabled = false;

    public function setDisabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    protected function disabledButton(): ?DisabledButton
    {
        return $this->disabled ? new DisabledButton : null;
    }
}

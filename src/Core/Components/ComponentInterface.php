<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Throwable;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Telepath component interface
 *
 * Has methods that implements lifecycle of app update proceed
 */
interface ComponentInterface
{
    /**
     * App start up (before updates proceed)
     */
    public function onCreated(): void;

    /**
     * Before resolving the concrete update
     */
    public function onBefore(Update $update): void;

    /**
     * If update resolving ends failure
     */
    public function onError(Update $update, Throwable $e): void;

    /**
     * After update proceed (failure or success)
     */
    public function onAfter(Update $update): void;

    /**
     * End of app lifecycle
     */
    public function onDestroy(): void;
}

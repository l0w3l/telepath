<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades\Resources;

use Illuminate\Support\Sleep;
use Lowel\Telepath\Enums\AsyncRequestEnum;
use Lowel\Telepath\Jobs\AsyncSpiritBoxRequest;
use RuntimeException;

/**
 * @template T
 */
final class AsyncSpiritBoxResource
{
    const SLEEP_MS = 100;

    const DEFAULT_TIMEOUT_MS = 5000;

    private mixed $response = null;

    private bool $resolved = false;

    public function __construct(public AsyncSpiritBoxRequest $spiritBoxRequestJob) {}

    /**
     * @return T
     */
    public function wait(int $timeoutMs = self::DEFAULT_TIMEOUT_MS): mixed
    {
        if ($this->resolved) {
            return $this->response;
        }

        $deadline = microtime(true) + ($timeoutMs / 1000);

        while ($this->spiritBoxRequestJob->status() === AsyncRequestEnum::PENDING) {
            if (microtime(true) >= $deadline) {
                throw new RuntimeException('Async SpiritBox request timed out.');
            }

            Sleep::for(self::SLEEP_MS)->milliseconds();
        }

        $this->response = $this->spiritBoxRequestJob->response();
        $this->resolved = true;

        return $this->response;
    }
}

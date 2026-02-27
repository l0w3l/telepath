<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades\Resources;

use Illuminate\Support\Sleep;
use Lowel\Telepath\Enums\AsyncRequestEnum;
use Lowel\Telepath\Jobs\AsyncSpiritBoxRequestJob;

/**
 * @template T
 */
final readonly class AsyncSpiritBoxResource
{
    const SLEEP_MS = 100;

    public function __construct(public AsyncSpiritBoxRequestJob $spiritBoxRequestJob) {}

    /**
     * @return T
     */
    public function wait(): mixed
    {
        static $response = null;

        if ($response === null) {
            while ($this->spiritBoxRequestJob->status() === AsyncRequestEnum::PENDING) {
                Sleep::for(self::SLEEP_MS)->milliseconds();
            }

            $response = $this->spiritBoxRequestJob->response();
        }

        return $response;
    }
}

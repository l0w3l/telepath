<?php

declare(strict_types=1);

namespace Lowel\Telepath\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Lowel\Telepath\Exceptions\TelegramAppException;
use Lowel\Telepath\Facades\Paranormal;
use Lowel\Telepath\TelegramAppFactoryInterface;
use Phptg\BotApi\Type\Update\Update;

class HandleTelegramUpdateRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public string $json
    ) {}

    public function handle(): void
    {
        try {
            app(TelegramAppFactoryInterface::class)
                ->webhook($this->json)->start();
        } catch (TelegramAppException $e) {
            Paranormal::catch(Update::fromJson($this->json), $e);
        }
    }
}

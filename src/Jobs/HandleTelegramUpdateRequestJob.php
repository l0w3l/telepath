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
use Psr\Http\Message\ServerRequestInterface;

class HandleTelegramUpdateRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public ServerRequestInterface $request
    ) {}

    public function handle(): void
    {
        try {
            app(TelegramAppFactoryInterface::class)
                ->webhook($this->request)->start();
        } catch (TelegramAppException $e) {
            Paranormal::catch(Update::fromServerRequest($this->request), $e);
        }
    }
}

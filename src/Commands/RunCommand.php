<?php

namespace Lowel\Telepath\Commands;

use Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Lowel\Telepath\Components\Context\Context;
use Lowel\Telepath\Core\Router\RequestFactory;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\TelegramException;
use Lowel\Telepath\Facades\Extrasense;
use Phptg\BotApi\FailResult;
use Phptg\BotApi\TelegramBotApi;

class RunCommand extends Command
{
    const int ERRORS_COUNT_THRESHOLD = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start up telegram long-pool process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $telegramBotApi = app()->make(TelegramBotApi::class);
        $context = app()->make(Context::class);

        $this->info('Start up telegram long-pool process...');

        config()->set('telepath.get_updates', true);

        pcntl_async_signals(true);

        $stopFlag = false;
        pcntl_signal(SIGINT, function () use (&$stopFlag) {
            $this->error('Interrupted');

            $stopFlag = true;
        });

        while (! $stopFlag) {
            pcntl_signal_dispatch();

            $updates = $this->getUpdates($telegramBotApi);

            foreach ($updates as $update) {
                $context->onBefore($update);

                foreach (UpdateTypeEnum::resolve($update) as $updateType) {
                    $context->setType($updateType);

                    $ogRequest = app('request');
                    $internalRequest = RequestFactory::fromUpdate($updateType, $update);

                    app()->instance('request', $internalRequest);

                    Route::dispatch($internalRequest);

                    app()->instance('request', $ogRequest);
                }

                $context->onAfter($update);
            }
        }
    }

    private function getUpdates(TelegramBotApi $telegramBotApi): Generator
    {
        static $lastUpdateId = null;

        $updates = $telegramBotApi->getUpdates($lastUpdateId, Extrasense::profile()->limit, Extrasense::profile()->timeout, Extrasense::profile()->allowedUpdates);

        if ($updates instanceof FailResult) {
            Log::error('Failed to retrieve updates', ['update' => $updates]);

            throw new TelegramException($updates);
        }

        foreach ($updates as $update) {
            $lastUpdateId = $update->updateId + 1;

            yield $update;
        }
    }
}

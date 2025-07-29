<?php

namespace Lowel\Telepath\Commands;

use Illuminate\Console\Command;
use Lowel\Telepath\TelegramAppFactoryInterface;

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
    public function handle(TelegramAppFactoryInterface $telegramAppFactory): int
    {
        $longPoolApp = $telegramAppFactory->longPooling();

        $this->info('Start up telegram long-pool process...');

        $errorCounter = 0;
        while (true) {
            try {
                $longPoolApp->start();

                $errorCounter = 0;
            } catch (\Throwable $e) {
                $this->error('Error in long-pool process: '.$e);
                $errorCounter++;

                if ($errorCounter >= self::ERRORS_COUNT_THRESHOLD) {
                    $this->error('Too many errors, stopping the process.');

                    return self::FAILURE;
                }

                sleep(5); // Wait before retrying
            }
        }
    }
}

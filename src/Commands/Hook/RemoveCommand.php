<?php

namespace Lowel\Telepath\Commands\Hook;

use Exception;
use Illuminate\Console\Command;
use Vjik\TelegramBot\Api\TelegramBotApi;

class RemoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:hook:remove {--d|drop : drop pending updates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Telegram hook';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $telegramBotApi = app(TelegramBotApi::class);

        try {
            $telegramBotApi->deleteWebhook($this->option('drop'));
            $this->info('Telegram hook removed successfully.');
        } catch (Exception $e) {
            $this->error('Failed to remove Telegram hook: '.$e->getMessage());
        }
    }
}

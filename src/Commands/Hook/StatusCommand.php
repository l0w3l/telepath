<?php

namespace Lowel\Telepath\Commands\Hook;

use Illuminate\Console\Command;
use Vjik\TelegramBot\Api\TelegramBotApi;

class StatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:hook:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram hook status';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $telegramBotApi = app(TelegramBotApi::class);

        $webhookInfo = $telegramBotApi->getWebhookInfo();

        $this->table([
            'Name', 'Value',
        ], [
            ['URL', $webhookInfo->url],
            ['Has custom certificate', $webhookInfo->hasCustomCertificate ? 'Yes' : 'No'],
            ['Pending update count', $webhookInfo->pendingUpdateCount],
            ['Last error date', $webhookInfo->lastErrorDate ? date('Y-m-d H:i:s', $webhookInfo->lastErrorDate->getTimestamp()) : 'N/A'],
            ['Last error message', $webhookInfo->lastErrorMessage ?? 'N/A'],
            ['Max connections', $webhookInfo->maxConnections ?? 'N/A'],
            ['Allowed updates', $webhookInfo->allowedUpdates ? implode(', ', $webhookInfo->allowedUpdates) : 'N/A'],
        ]);
    }
}

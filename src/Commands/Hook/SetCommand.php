<?php

namespace Lowel\Telepath\Commands\Hook;

use Illuminate\Console\Command;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;

class SetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:hook:set {hook=default} {--a|allow=* : List of allowed updates} {--d|drop : drop pending updates} {--m|max-connections=100 : Maximum number of connections}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a Telegram hook';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $telegramBotApi = app(TelegramBotApi::class);

        $telegramBotApi->setWebhook(
            url: $this->argument('hook') === 'default' ? config('app.url').'/api/webhook' : $this->argument('hook'),
            maxConnections: (int) $this->option('max-connections'),
            allowUpdates: empty($this->option('allow')) ? UpdateTypeEnum::toArray() : UpdateTypeEnum::toArray($this->option('allow')),
            dropPendingUpdates: (bool) $this->option('drop'),
        );

        $this->info('Telegram hook set successfully to: '.$this->argument('hook'));
    }
}

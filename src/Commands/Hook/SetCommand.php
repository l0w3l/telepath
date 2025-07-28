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
    protected $signature = 'lowel:telegram:hook:set {hook} {--a|allow=* : List of allowed updates} {--d|drop : drop pending updates} {--m|max-connections=1 : Maximum number of connections}';

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
            url: $this->argument('hook'),
            maxConnections: $this->hasOption('max-connections') ? (int) $this->option('max-connections') : null,
            allowUpdates: $this->hasOption('allow') ? UpdateTypeEnum::toArray(explode(',', ((string) $this->option('allow')))) : UpdateTypeEnum::toArray(),
            dropPendingUpdates: $this->hasOption('drop') ? (bool) $this->option('drop') : null,
        );

        $this->info('Telegram hook set successfully to: '.$this->argument('hook'));
    }
}

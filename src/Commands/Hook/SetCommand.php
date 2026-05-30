<?php

namespace Lowel\Telepath\Commands\Hook;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\TelegramHookSecretKeyNotFoundException;
use Phptg\BotApi\TelegramBotApi;

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

        $secretKey = config('telepath.hook.secret');

        if (! $secretKey) {
            $this->warn('TELEPATH_SECRET was not found.');

            if ($this->confirm('Do you want to generate it?', true)) {
                Artisan::call(KeyGenerateCommand::class);
            }
        }

        $url = $this->argument('hook') === 'default' ? url('/telepath/webhook') : $this->argument('hook');

        $telegramBotApi->setWebhook(
            url: $url,
            maxConnections: (int) $this->option('max-connections'),
            allowUpdates: empty($this->option('allow')) ? UpdateTypeEnum::toArray() : UpdateTypeEnum::toArray($this->option('allow')),
            dropPendingUpdates: (bool) $this->option('drop'),
            secretToken: config('telepath.hook.secret') ?? throw new TelegramHookSecretKeyNotFoundException
        );

        $this->info('Telegram hook set successfully to: '.$url);
    }
}

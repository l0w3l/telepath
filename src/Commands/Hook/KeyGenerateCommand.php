<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Hook;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KeyGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate telepath secret token for hook';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $existingKey = config('telepath.hook.secret');

        if ($existingKey) {
            $this->warn('TELEPATH_SECRET already exists.');

            // Спрашиваем нужно ли перегенерировать
            if (! $this->confirm('Do you want to regenerate the key?', false)) {
                $this->info('Operation cancelled.');

                return;
            }
        }

        $path = base_path('.env');
        $key = 'TELEPATH_SECRET';
        $value = Str::random(64);
        $content = File::get($path);

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $content
            );
        } else {
            $content .= PHP_EOL."{$key}={$value}";
        }

        File::put($path, $content);

        config()->set('telepath.hook.secret', $value);

        $this->info('TELEPATH_SECRET generated successfully.');
    }
}

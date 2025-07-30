<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands;

use Lowel\Telepath\Commands\Abstract\FileGenerator\AbstractMakeFilesCommand;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateTelegramMiddlewareFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramMiddlewareFileMetadata;

class MakeMiddlewareCommand extends AbstractMakeFilesCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:make:middleware {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new telegram middleware file';

    public function getActions(string $argument): array
    {
        return [
            new CreateTelegramMiddlewareFileAction(new TelegramMiddlewareFileMetadata, $this->argument('name')),
        ];
    }
}

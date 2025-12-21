<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Handler;

use Lowel\Telepath\Commands\Abstract\FileGenerator\AbstractMakeFilesCommand;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateTelegramHandlerFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramHandlerFileMetadata;

class MakeHandlerCommand extends AbstractMakeFilesCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:make:handler {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new telegram handler';

    public function getActions(string $argument): array
    {
        return [
            new CreateTelegramHandlerFileAction(new TelegramHandlerFileMetadata, $this->argument('name')),
        ];
    }
}

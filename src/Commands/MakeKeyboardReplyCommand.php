<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands;

use Lowel\Telepath\Commands\Abstract\FileGenerator\AbstractMakeFilesCommand;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateReplyTelegramButtonFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateReplyTelegramKeyboardFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramReplyButtonFileMetadata;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramReplyKeyboardFileMetadata;

class MakeKeyboardReplyCommand extends AbstractMakeFilesCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:make:keyboard:reply {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new smart reply keyboard';

    public function getActions(string $argument): array
    {
        return [
            new CreateReplyTelegramButtonFileAction(new TelegramReplyButtonFileMetadata, $this->argument('name')),
            new CreateReplyTelegramKeyboardFileAction(new TelegramReplyKeyboardFileMetadata, $this->argument('name')),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Keyboard;

use Lowel\Telepath\Commands\Abstract\FileGenerator\AbstractMakeFilesCommand;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateInlineTelegramButtonFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateInlineTelegramKeyboardFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramInlineButtonFileMetadata;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramInlineKeyboardFileMetadata;

class MakeKeyboardInlineCommand extends AbstractMakeFilesCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:make:keyboard:inline {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new smart inline keyboard';

    public function getActions(string $argument): array
    {
        return [
            new CreateInlineTelegramButtonFileAction(new TelegramInlineButtonFileMetadata, $this->argument('name')),
            new CreateInlineTelegramKeyboardFileAction(new TelegramInlineKeyboardFileMetadata, $this->argument('name')),
        ];
    }
}

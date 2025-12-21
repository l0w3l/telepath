<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Conversation;

use Lowel\Telepath\Commands\Abstract\FileGenerator\AbstractMakeFilesCommand;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram\CreateTelegramConversationFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram\TelegramConversationFileMetadata;

class MakeConversationCommand extends AbstractMakeFilesCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:make:conversation {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new telegram conversation';

    public function getActions(string $argument): array
    {
        return [
            new CreateTelegramConversationFileAction(new TelegramConversationFileMetadata, $this->argument('name')),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator;

use Illuminate\Console\Command;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Exceptions\Commands\FileDuplicatedException;

abstract class AbstractMakeFilesCommand extends Command
{
    /**
     * @return AbstractCreateFileAction[]
     */
    abstract public function getActions(string $argument): array;

    public function handle(): ?bool
    {
        $argument = $this->argument('name');

        foreach ($this->getActions($argument) as $action) {
            try {
                $fileDestination = $action->create();

                $this->components->info(sprintf('%s [%s] created successfully.', $action->className, $fileDestination));
            } catch (FileDuplicatedException $e) {
                $this->components->error($action->classPath.' already exists.');
            }

        }

        return true;
    }
}

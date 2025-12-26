<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Conversation\TelegramConversationInterface;

readonly class CreateTelegramConversationFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace."\\$this->argumentName");

        $classGenerator
            ->setUse($this->namespace."\\$this->argumentName\\Promises\\ExamplePromise")
            ->setImplements(TelegramConversationInterface::class)
            ->setFunction("function promises(): array\n{\n{$classGenerator->spaces}return [new ExamplePromise];\n}");

        $this->saveInDirectoryName($classGenerator->generate());

        return $this->classPath;
    }
}

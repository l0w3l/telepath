<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Reply\AbstractReplyButton;

readonly class CreateReplyTelegramButtonFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator('ExampleReplyButton', $this->namespace."\\$this->argumentName\\Buttons");

        $classGenerator
            ->setExtends(AbstractReplyButton::class)
            ->setFunction("function text(array \$args = []): int|string|callable\n{\n{$classGenerator->spaces}return 'example';\n}");

        $this->createDirectoryIfNotExists();

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}");
        }

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}/Buttons")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}/Buttons");
        }

        $this->save($this->folderPath."{$this->argumentName}/Buttons/ExampleReplyButton.php", $classGenerator->generate());

        return $this->classPath;
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;

readonly class CreateInlineTelegramButtonFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator('ExampleInlineButton', $this->namespace."\\$this->argumentName\\Buttons");

        $classGenerator
            ->setUse(SpiritBox::class)
            ->setExtends(AbstractCallbackButton::class)
            ->setFunction("function handle(): callable\n{\n{$classGenerator->spaces}return static function() {\n{$classGenerator->spaces}{$classGenerator->spaces}SpiritBox::sendMessage('example text');\n{$classGenerator->spaces}};\n}")
            ->setFunction("function text(array \$args = []): int|string|callable\n{\n{$classGenerator->spaces}return 'example';\n}");

        $this->createDirectoryIfNotExists();

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}");
        }

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}/Buttons")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}/Buttons");
        }

        $this->save($this->folderPath."{$this->argumentName}/Buttons/ExampleInlineButton.php", $classGenerator->generate());

        return $this->classPath;
    }
}

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline\AbstractCallbackButton;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Chat;

readonly class CreateInlineTelegramButtonFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator('ExampleInlineButton', $this->namespace."\\$this->argumentName\\Buttons");

        $classGenerator
            ->setUse(TelegramBotApi::class)
            ->setUse(Chat::class)
            ->setExtends(AbstractCallbackButton::class)
            ->setFunction("function handle(): callable\n{\n{$classGenerator->spaces}return function(TelegramBotApi \$api, Chat \$chat) {\n{$classGenerator->spaces}{$classGenerator->spaces}\$api->sendMessage(\$chat->id, 'example text');\n{$classGenerator->spaces}};\n}")
            ->setFunction("function text(array \$args = []): int|string|callable\n{\n{$classGenerator->spaces}return 'test';\n}");

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

<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Conversation\Promise\AbstractTelegramPromise;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Chat;

readonly class CreateTelegramPromiseFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator('ExamplePromise', $this->namespace."\\$this->argumentName\\Promises");

        $classGenerator
            ->setUse(TelegramBotApi::class)
            ->setUse(Chat::class)
            ->setUse(\Throwable::class)
            ->setExtends(AbstractTelegramPromise::class)
            ->setFunction("function resolve(): callable\n{\n{$classGenerator->spaces}return function(TelegramBotApi \$api, Chat \$chat) {\n{$classGenerator->spaces}{$classGenerator->spaces}\$api->sendMessage(\$chat->id, 'example text');\n{$classGenerator->spaces}};\n}")
            ->setFunction("function reject(Throwable \$error): ?callable\n{\n{$classGenerator->spaces}return function () {};\n}");

        $this->createDirectoryIfNotExists();

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}");
        }

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}/Promises")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}/Promises");
        }

        $this->save($this->folderPath."{$this->argumentName}/Promises/ExamplePromise.php", $classGenerator->generate());

        return $this->classPath;
    }
}

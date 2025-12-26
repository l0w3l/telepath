<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

readonly class CreateTelegramHandlerFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace);

        $classGenerator
            ->setUse(TelegramBotApi::class)
            ->setUse(Update::class)
            ->setExtends(AbstractTelegramHandler::class)
            ->setFunction("function __invoke(TelegramBotApi \$telegramBotApi, Update \$update): void\n{\n{$classGenerator->spaces}//...\n}");

        $this->createDirectoryIfNotExists();

        $this->save($this->classPath, $classGenerator->generate());

        return $this->classPath;
    }
}

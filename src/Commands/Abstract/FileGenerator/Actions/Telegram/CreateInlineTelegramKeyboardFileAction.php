<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\AbstractInlineKeyboard;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Attributes\InlineButtonAttribute;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

readonly class CreateInlineTelegramKeyboardFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace);

        $classGenerator
            ->setUse(TelegramBotApi::class)
            ->setUse(InlineButtonAttribute::class)
            ->setUse(Update::class)
            ->setExtends(AbstractInlineKeyboard::class)
            ->setFunction("#[InlineButtonAttribute(text: \"test\")]\nfunction testButton(TelegramBotApi \$telegramBotApi, Update \$update): void\n{\n{$classGenerator->spaces}//...\n}");

        $this->createDirectoryIfNotExists();

        $this->save($this->classPath, $classGenerator->generate());

        return $this->classPath;
    }
}

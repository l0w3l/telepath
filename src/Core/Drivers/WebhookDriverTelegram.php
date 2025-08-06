<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Illuminate\Http\Request;
use Lowel\Telepath\Core\Drivers\Traits\UpdateHandlerTrait;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

final readonly class WebhookDriverTelegram implements TelegramAppDriverInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        private Request $request
    ) {}

    public function proceed(TelegramBotApi $telegramBotApi, TelegramRouterResolverInterface $routerResolver): void
    {
        $content = $this->request->getContent();

        if (json_validate($content)) {
            $update = Update::fromJson($content);

            $this->processUpdate($update, $telegramBotApi, $routerResolver);
        }
    }
}

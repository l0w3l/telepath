<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\GlobalAppContext;

use Lowel\Telepath\Exceptions\ChatNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\MessageNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UserNotFoundInCurrentContextException;
use Vjik\TelegramBot\Api\Type\Chat;
use Vjik\TelegramBot\Api\Type\Message;
use Vjik\TelegramBot\Api\Type\Update\Update;
use Vjik\TelegramBot\Api\Type\User;

interface GlobalAppContextInterface
{
    /**
     * @throws UpdateNotFoundInCurrentContextException
     */
    public function update(): Update;

    /**
     * @throws UserNotFoundInCurrentContextException
     * @throws UpdateNotFoundInCurrentContextException
     */
    public function user(): User;

    /**
     * @throws MessageNotFoundInCurrentContextException
     * @throws UpdateNotFoundInCurrentContextException
     */
    public function message(): Message;

    /**
     * @throws ChatNotFoundInCurrentContextException
     * @throws UpdateNotFoundInCurrentContextException
     */
    public function chat(): Chat;

    public function isLongPooling(): bool;

    public function isWebhook(): bool;
}

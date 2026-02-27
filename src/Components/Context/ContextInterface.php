<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\Context;

use Lowel\Telepath\Config\Profile;
use Lowel\Telepath\Exceptions\ChatNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\MessageNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UserNotFoundInCurrentContextException;
use Phptg\BotApi\Type\Chat;
use Phptg\BotApi\Type\Message;
use Phptg\BotApi\Type\Update\Update;
use Phptg\BotApi\Type\User;

/**
 * Context interface for current application state.
 */
interface ContextInterface
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

    public function profile(?string $profileKey = null): Profile;

    public function imaginate(Update $dream, callable $callback): void;
}

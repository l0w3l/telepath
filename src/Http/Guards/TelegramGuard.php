<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Guards;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UserNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Models\TgUser;
use Phptg\BotApi\Type\Update\Update;

class TelegramGuard implements Guard
{
    protected ?Authenticatable $tgUser = null;

    public function __construct(public Update $update) {}

    /**
     * @throws UserNotFoundInCurrentContextException
     * @throws UpdateNotFoundInCurrentContextException
     */
    public function user(): ?Authenticatable
    {
        if ($this->tgUser) {
            return $this->tgUser;
        }

        $telegramId = Extrasense::replicate($this->update)->user()->id;

        $this->tgUser = TgUser::where('telegram_id', $telegramId)->first();

        return $this->tgUser;
    }

    public function id(): int|string|null
    {
        return $this->user()?->getAuthIdentifier();
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function guest(): bool
    {
        return ! $this->check();
    }

    public function hasUser(): bool
    {
        return $this->tgUser !== null;
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function setUser(Authenticatable $user): static
    {
        $this->tgUser = $user;

        return $this;
    }
}

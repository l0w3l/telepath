<?php

declare(strict_types=1);

namespace Lowel\Telepath\Http\Middlewares\Authorization;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lowel\Telepath\Exceptions\UserNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Models\TgUser;

class TelegramUserAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $rawUser = Extrasense::user();
        } catch (UserNotFoundInCurrentContextException) {
            return $next($request);
        }

        $data = [
            'first_name' => $rawUser->firstName,
            'last_name' => $rawUser->lastName,
            'username' => $rawUser->username,
            'language_code' => $rawUser->languageCode ?? 'en',
            'is_bot' => $rawUser->isBot,
        ];

        $guard = Auth::guard('telegram');

        try {
            $tgUser = $guard->user();
        } catch (\Throwable) {
            return $next($request);
        }

        if ($tgUser) {
            $tgUser->fill($data);

            if ($tgUser->isDirty()) {
                $tgUser->save();
            }
        } else {
            $tgUser = TgUser::create([
                'telegram_id' => $rawUser->id,
                ...$data,
            ]);
        }

        Auth::guard('telegram')->setUser($tgUser);

        return $next($request);
    }
}

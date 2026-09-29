<?php

use Lowel\Telepath\Core\Router\Keyboard\Buttons\Reply\AbstractReplyButton;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\Telepath;
use Phptg\BotApi\Type\Update\Update;

class TelegramDispatcherTestMiddlewareOrder
{
    /**
     * @var list<string>
     */
    public static array $order = [];
}

class TelegramDispatcherTestGroupMiddleware
{
    public function handle($request, Closure $next)
    {
        TelegramDispatcherTestMiddlewareOrder::$order[] = 'group-before';

        $response = $next($request);

        TelegramDispatcherTestMiddlewareOrder::$order[] = 'group-after';

        return $response;
    }
}

class TelegramDispatcherTestRouteMiddleware
{
    public function handle($request, Closure $next)
    {
        TelegramDispatcherTestMiddlewareOrder::$order[] = 'route-before';

        $response = $next($request);

        TelegramDispatcherTestMiddlewareOrder::$order[] = 'route-after';

        return $response;
    }
}

class TelegramDispatcherTestHandler
{
    public function handle(string $text): void
    {
        TelegramDispatcherTestMiddlewareOrder::$order[] = $text;
    }
}

class TelegramDispatcherTestAliasMiddleware
{
    public function handle($request, Closure $next, string $label)
    {
        TelegramDispatcherTestMiddlewareOrder::$order[] = $label;

        return $next($request);
    }
}

class TelegramDispatcherTestReplyButton extends AbstractReplyButton
{
    protected string $text = 'reply';

    public function handle(): void
    {
        TelegramDispatcherTestMiddlewareOrder::$order[] = 'reply';
    }
}

test('first registered matching route wins', function (): void {
    $hits = [];

    $this->updatesMockBuilder->addMessage('same');

    $this->withinTelegramRoutes(function () use (&$hits): void {
        Telepath::onMessage(function () use (&$hits): void {
            $hits[] = 'first';
        }, 'same');

        Telepath::onMessage(function () use (&$hits): void {
            $hits[] = 'second';
        }, 'same');
    });

    $this->dispatchTelegramUpdates();

    expect($hits)->toBe(['first']);
});

test('unmatched route returns without invoking unrelated handlers', function (): void {
    $called = false;

    $this->updatesMockBuilder->addMessage('actual');

    $this->withinTelegramRoutes(function () use (&$called): void {
        Telepath::onMessage(function () use (&$called): void {
            $called = true;
        }, 'expected');
    });

    $this->dispatchTelegramUpdates();

    expect($called)->toBeFalse();
});

test('command message and callback query payloads match through dispatcher', function (): void {
    $hits = [];

    $this->updatesMockBuilder
        ->addMessage('/start')
        ->addMessage('plain')
        ->addCallbackQuery('profile:open');

    $this->withinTelegramRoutes(function () use (&$hits): void {
        Telepath::onCommand(function (Update $update) use (&$hits): void {
            $hits[] = 'command:'.$update->message->text;
        }, 'start');

        Telepath::onMessage(function (Update $update) use (&$hits): void {
            $hits[] = 'message:'.$update->message->text;
        }, 'plain');

        Telepath::onCallbackQuery(function (Update $update) use (&$hits): void {
            $hits[] = 'callback:'.$update->callbackQuery->data;
        }, 'profile:open');
    });

    $this->dispatchTelegramUpdates();

    expect($hits)->toBe([
        'command:/start',
        'message:plain',
        'callback:profile:open',
    ]);
});

test('group and route middleware execute in deterministic order', function (): void {
    TelegramDispatcherTestMiddlewareOrder::$order = [];

    $this->updatesMockBuilder->addMessage('middleware');

    $this->withinTelegramRoutes(function (): void {
        Telepath::group(['middleware' => TelegramDispatcherTestGroupMiddleware::class], function (): void {
            Telepath::onMessage(function (): void {
                TelegramDispatcherTestMiddlewareOrder::$order[] = 'handler';
            }, 'middleware')->middleware(TelegramDispatcherTestRouteMiddleware::class);
        });
    });

    $this->dispatchTelegramUpdates();

    expect(TelegramDispatcherTestMiddlewareOrder::$order)->toBe([
        'group-before',
        'route-before',
        'handler',
        'route-after',
        'group-after',
    ]);
});

test('class method handlers receive the matched payload', function (): void {
    TelegramDispatcherTestMiddlewareOrder::$order = [];
    $this->updatesMockBuilder->addMessage('hello');

    $this->withinTelegramRoutes(function (): void {
        Telepath::onMessage([TelegramDispatcherTestHandler::class, 'handle'], '[a-z]+');
    });

    $this->dispatchTelegramUpdates();

    expect(TelegramDispatcherTestMiddlewareOrder::$order)->toBe(['hello']);
});

test('reply button class handlers run through the dispatcher', function (): void {
    TelegramDispatcherTestMiddlewareOrder::$order = [];
    $this->updatesMockBuilder->addMessage('reply');

    $this->withinTelegramRoutes(function (): void {
        Telepath::button(TelegramDispatcherTestReplyButton::class);
    });

    $this->dispatchTelegramUpdates();

    expect(TelegramDispatcherTestMiddlewareOrder::$order)->toBe(['reply']);
});

test('anchored callback button patterns match their payload', function (): void {
    $called = false;
    $this->updatesMockBuilder->addCallbackQuery('button:open');

    $this->withinTelegramRoutes(function () use (&$called): void {
        Telepath::onCallbackQuery(function () use (&$called): void {
            $called = true;
        }, '^button:.*$');
    });

    $this->dispatchTelegramUpdates();

    expect($called)->toBeTrue();
});

test('redirect restores the outer update type and request', function (): void {
    $types = [];
    $this->updatesMockBuilder->addMessage('outer');

    $this->withinTelegramRoutes(function () use (&$types): void {
        Telepath::onMessage(function () use (&$types): void {
            $outerRequest = request();
            Telepath::redirect('inner', updateTypeEnum: UpdateTypeEnum::CALLBACK_QUERY);
            $types[] = Extrasense::type();
            expect(request())->toBe($outerRequest);
        }, 'outer');

        Telepath::onCallbackQuery(function () use (&$types): void {
            $types[] = Extrasense::type();
        }, 'inner');
    });

    $this->dispatchTelegramUpdates();

    expect($types)->toBe([UpdateTypeEnum::CALLBACK_QUERY, UpdateTypeEnum::MESSAGE]);
});

test('middleware aliases and arguments work in groups and routes', function (): void {
    TelegramDispatcherTestMiddlewareOrder::$order = [];
    app('router')->aliasMiddleware('telegram-test', TelegramDispatcherTestAliasMiddleware::class);
    $this->updatesMockBuilder->addMessage('alias');

    $this->withinTelegramRoutes(function (): void {
        Telepath::group(['middleware' => 'telegram-test:group'], function (): void {
            Telepath::onMessage(function (): void {
                TelegramDispatcherTestMiddlewareOrder::$order[] = 'handler';
            }, 'alias')->middleware('telegram-test:route');
        });
    });

    $this->dispatchTelegramUpdates();

    expect(TelegramDispatcherTestMiddlewareOrder::$order)->toBe(['group', 'route', 'handler']);
});

test('closure handlers receive payload even when the argument has a different name', function (): void {
    $received = null;
    $this->updatesMockBuilder->addMessage('value');

    $this->withinTelegramRoutes(function () use (&$received): void {
        Telepath::onMessage(function (string $text) use (&$received): void {
            $received = $text;
        }, '[a-z]+');
    });

    $this->dispatchTelegramUpdates();

    expect($received)->toBe('value');
});

test('constraints added to returned routes affect matching', function (): void {
    $hits = [];
    $this->updatesMockBuilder->addMessage('blocked')->addMessage('allowed');

    $this->withinTelegramRoutes(function () use (&$hits): void {
        Telepath::onMessage(function () use (&$hits): void {
            $hits[] = 'constrained';
        })->where('any', 'allowed');

        Telepath::onMessage(function () use (&$hits): void {
            $hits[] = 'fallback';
        });
    });

    $this->dispatchTelegramUpdates();

    expect($hits)->toBe(['fallback', 'constrained']);
});

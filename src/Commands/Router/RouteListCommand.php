<?php

namespace Lowel\Telepath\Commands\Router;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;

class RouteListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telepath:route:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram router list';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $telegramRouterResolver = app(TelegramRouterResolverInterface::class);

        $executorsCollection = $telegramRouterResolver->getExecutors();

        $rows = [];
        foreach ($executorsCollection->handlers() as $executor) {
            $params = $executor->params();
            $rows[] = [
                $params->getUpdateTypeEnum()->value,
                $params->getName(),
                (Str::contains($params->getHandler()::class, '@anonymous')) ? 'Closure' : $params->getHandler()::class,
                implode(',', array_map(fn (object $middleware) => $middleware::class, $params->getMiddlewares())),
                $params->hasConversation() ? 'Yes' : '',
            ];
        }

        $this->table([
            'Type', 'Name', 'Handler', 'Middlewares', 'Conversation',
        ], $rows);
    }
}

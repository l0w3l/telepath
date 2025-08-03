<?php

use Illuminate\Http\Request;
use Lowel\Telepath\TelegramAppFactoryInterface;
use Mockery\MockInterface;

beforeEach(function (): void {});

test('test', function (): void {
    $this->mock(Request::class, function (MockInterface $mock): void {
        $mock->shouldReceive('getContent')
            ->once()
            ->andReturn(
                json_encode([
                    'update_id' => 123456789,
                    'message' => [
                        'message_id' => 1,
                        'from' => [
                            'id' => 123456789,
                            'is_bot' => false,
                            'first_name' => 'Test',
                            'username' => 'testuser',
                        ],
                        'chat' => [
                            'id' => 123456789,
                            'first_name' => 'Test',
                            'username' => 'testuser',
                            'type' => 'private',
                        ],
                        'date' => time(),
                        'text' => 'Hello, world!',
                    ],
                ])
            );
    });

    $telegramAppFactory = App::make(TelegramAppFactoryInterface::class);

    $telegramAppFactory->webhook()->start();
});

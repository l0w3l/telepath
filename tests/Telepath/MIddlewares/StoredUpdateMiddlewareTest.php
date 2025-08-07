<?php

use Illuminate\Http\Request;
use Lowel\Telepath\Facades\Telepath;
use Lowel\Telepath\Middlewares\StoredUpdatesMiddleware;
use Lowel\Telepath\Models\TelepathStoredUpdate;
use Lowel\Telepath\TelegramAppFactoryInterface;
use Mockery\MockInterface;

beforeEach(function () {
    $this->artisan('vendor:publish', [
        '--tag' => 'telepath-migrations',
    ]);
    $this->artisan('migrate:fresh');

    Telepath::middleware(StoredUpdatesMiddleware::class)
        ->group(function () {
            Telepath::onMessage(function () {});
        });
    Telepath::onMessageEdit(function () {});

    $this->mockUpdateStubOnMessage = [
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
    ];

    $this->mockUpdateStubOnEditMessage = [
        'update_id' => 987654321,
        'edited_message' => [
            'message_id' => 1,
            'from' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'Test',
                'username' => 'testuser',
            ],
            'chat' => [
                'id' => 987654321,
                'first_name' => 'Test',
                'username' => 'testuser',
                'type' => 'private',
            ],
            'date' => time(),
            'text' => 'Hello, world edit!',
        ],
    ];
});

test('stored updates test', function () {
    $telegramAppFactory = App::make(TelegramAppFactoryInterface::class);

    $this->mock(Request::class, function (MockInterface $mock): void {
        $mock->shouldReceive('getContent')
            ->once()
            ->andReturn(
                json_encode($this->mockUpdateStubOnMessage)
            );
    });

    $telegramAppFactory->webhook()->start();

    $this->mock(Request::class, function (MockInterface $mock): void {
        $mock->shouldReceive('getContent')
            ->once()
            ->andReturn(
                json_encode($this->mockUpdateStubOnEditMessage)
            );
    });

    $telegramAppFactory->webhook()->start();

    expect(TelepathStoredUpdate::whereJsonContains('instance->update_id', $this->mockUpdateStubOnMessage['update_id'])->exists())->toBeTrue()
        ->and(TelepathStoredUpdate::whereJsonContains('instance->update_id', $this->mockUpdateStubOnEditMessage['update_id'])->exists())->toBeFalse();
});

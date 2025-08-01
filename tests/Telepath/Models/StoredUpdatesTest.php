<?php

use Lowel\Telepath\Models\TelepathStoredUpdates;
use Vjik\TelegramBot\Api\Type\Update\Update;

beforeEach(function (): void {
    $this->artisan('migrate:fresh');
});

test('test update cast attribute', function (): void {
    $updateArray = [
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
    $updateRawJson = json_encode($updateArray);
    $update = Update::fromJson($updateRawJson);

    // Update instance
    $storedUpdate = new TelepathStoredUpdates([
        'instance' => $update,
    ]);
    $storedUpdateArray = new TelepathStoredUpdates([
        'instance' => $updateArray,
    ]);
    $storedUpdateRawJson = new TelepathStoredUpdates([
        'instance' => $updateRawJson,
    ]);

    $storedUpdate->save();
    $storedUpdateArray->save();
    $storedUpdateRawJson->save();

    expect($storedUpdate->refresh()->instance)
        ->and($storedUpdateArray->refresh()->instance)
        ->and($storedUpdateRawJson->refresh()->instance)
        ->toBeInstanceOf(Update::class);
});

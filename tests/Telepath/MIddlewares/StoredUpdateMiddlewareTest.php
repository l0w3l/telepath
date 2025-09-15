<?php

use Lowel\Telepath\Facades\Telepath;
use Lowel\Telepath\Middlewares\StoredUpdatesMiddleware;
use Lowel\Telepath\Models\TelepathStoredUpdate;
use Lowel\Telepath\TelegramAppFactoryInterface;

beforeEach(function () {
    $this->artisan('migrate:fresh')->run();
});

test('stored updates test', function () {

    $updatesMocker = $this->updatesMockBuilder
        ->addMessage($this->name())
        ->addEditedMessage($this->name());

    $updatesMocker->mock();

    Telepath::middleware(StoredUpdatesMiddleware::class)
        ->group(function () {
            Telepath::onMessage(function () {});
        });
    Telepath::onMessageEdit(function () {});

    App::make(TelegramAppFactoryInterface::class)
        ->longPooling()->start();

    expect(TelepathStoredUpdate::whereJsonContains('instance->update_id', $updatesMocker->getArray()[0]['update_id'])->exists())->toBeTrue()
        ->and(TelepathStoredUpdate::whereJsonContains('instance->update_id', $updatesMocker->getArray()[1]['update_id'])->exists())->toBeFalse();
});

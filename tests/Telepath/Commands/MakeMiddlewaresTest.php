<?php

use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::deleteDirectory(app_path('Telegram/Middlewares'));
});

test('test', function (): void {
    expect(app_path('Telegram/Middlewares/TestMiddleware.php'))->not->toBeFile();

    $this->artisan('telepath:make:middleware', [
        'name' => 'Test',
    ]);

    expect(app_path('Telegram/Middlewares/TestMiddleware.php'))->toBeFile();
});

test('test deep', function (): void {
    expect(app_path('Telegram/Middlewares/Test/TestMiddleware.php'))->not->toBeFile();

    $this->artisan('telepath:make:middleware', [
        'name' => 'Test/Test',
    ]);

    expect(app_path('Telegram/Middlewares/Test/TestMiddleware.php'))->toBeFile();
});

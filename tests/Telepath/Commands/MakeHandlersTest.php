<?php

use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::deleteDirectory(app_path('Telegram/Handlers'));
});

test('test', function (): void {
    expect(app_path('Telegram/Handlers/TestHandler.php'))->not->toBeFile();

    $this->artisan('telepath:make:handler', [
        'name' => 'Test',
    ]);

    expect(app_path('Telegram/Handlers/TestHandler.php'))->toBeFile();
});

test('test deep', function (): void {
    expect(app_path('Telegram/Handlers/Test/TestHandler.php'))->not->toBeFile();

    $this->artisan('telepath:make:handler', [
        'name' => 'Test/Test',
    ]);

    expect(app_path('Telegram/Handlers/Test/TestHandler.php'))->toBeFile();
});

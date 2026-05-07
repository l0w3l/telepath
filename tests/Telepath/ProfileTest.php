<?php

use Illuminate\Support\Facades\Config;
use Lowel\Telepath\Config\Profile;

it('caches profile properties', function () {
    Config::set('telepath.profiles.default.token', 'test-token');

    $profile = new Profile('default');

    // First access
    expect($profile->token)->toBe('test-token');

    // Change config
    Config::set('telepath.profiles.default.token', 'new-token');

    // Second access should return cached value
    expect($profile->token)->toBe('test-token');
});

it('handles null values in cache', function () {
    Config::set('telepath.profiles.default.chat_id_fallback', null);

    $profile = new Profile('default');

    // First access
    expect($profile->chatIdFallback)->toBeNull();

    // Change config
    Config::set('telepath.profiles.default.chat_id_fallback', 123);

    // Second access should return cached null
    expect($profile->chatIdFallback)->toBeNull();
});

it('uses mutators and caches the result', function () {
    Config::set('telepath.profiles.default.allowed_updates', 'message,callback_query');

    $profile = new Profile('default');

    // First access
    $updates = $profile->allowedUpdates;
    expect($updates)->toBe(['message', 'callback_query']);

    // Change config
    Config::set('telepath.profiles.default.allowed_updates', 'message');

    // Second access should return cached array
    expect($profile->allowedUpdates)->toBe(['message', 'callback_query']);
});

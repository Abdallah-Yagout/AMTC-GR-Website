<?php

use App\Support\ProfileFormOptions;

uses(Tests\TestCase::class);

test('profile form options return empty string for null values', function () {
    expect(ProfileFormOptions::labelFor('skill_level', null))->toBe('')
        ->and(ProfileFormOptions::formatBoolean(null))->toBe('')
        ->and(ProfileFormOptions::formatList(null, 'motivation'))->toBe('');
});

test('profile form options format known select values', function () {
    expect(ProfileFormOptions::labelFor('skill_level', 'intermediate'))->toBe('Intermediate')
        ->and(ProfileFormOptions::labelFor('gender', 'male'))->toBe('Male')
        ->and(ProfileFormOptions::formatBoolean(true))->toBe('Yes')
        ->and(ProfileFormOptions::formatBoolean(false))->toBe('No');
});

test('profile form options format list values with labels', function () {
    $formatted = ProfileFormOptions::formatList(['prizes', 'challenge'], 'motivation');

    expect($formatted)->toBe('Prizes, Challenge & Competition');
});

test('profile form options fall back to raw value for unknown keys', function () {
    expect(ProfileFormOptions::labelFor('skill_level', 'custom_value'))->toBe('custom_value');
});

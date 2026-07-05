<?php

use App\Models\Profile;
use App\Support\ProfileCompletion;

uses(Tests\TestCase::class);

function completeProfileAttributes(): array
{
    return [
        'birthdate' => '1998-05-10',
        'whatsapp' => '0500000001',
        'gender' => 'male',
        'city' => 'Sanaa',
        'skill_level' => 'intermediate',
        'primary_platform' => 'ps5',
        'weekly_hours' => '10-15',
        'favorite_games' => ['gran_turismo_7'],
        'gt7_ranking' => '50000',
        'toyota_gr_knowledge' => 'intermediate',
        'favorite_car' => 'GR Yaris',
        'participated_before' => false,
        'heard_about' => 'social_media',
        'motivation' => ['competition'],
        'preferred_time' => 'evening',
    ];
}

test('profile completion is zero when profile is missing', function () {
    $completion = ProfileCompletion::for(null);

    expect($completion->percentage())->toBe(0)
        ->and($completion->completedCount())->toBe(0)
        ->and($completion->totalCount())->toBe(15)
        ->and($completion->remainingCount())->toBe(15)
        ->and($completion->isComplete())->toBeFalse();
});

test('profile completion is zero when profile is empty', function () {
    $completion = ProfileCompletion::for(new Profile);

    expect($completion->percentage())->toBe(0)
        ->and($completion->isComplete())->toBeFalse();
});

test('profile completion reaches one hundred when all required fields are filled', function () {
    $completion = ProfileCompletion::for(new Profile(completeProfileAttributes()));

    expect($completion->percentage())->toBe(100)
        ->and($completion->completedCount())->toBe(15)
        ->and($completion->remainingCount())->toBe(0)
        ->and($completion->isComplete())->toBeTrue();
});

test('basic info section requires birthdate gender and city', function () {
    $profile = new Profile([
        'birthdate' => '1998-05-10',
        'gender' => 'male',
    ]);

    $completion = ProfileCompletion::for($profile);

    expect($completion->isSectionComplete('basic-info'))->toBeFalse()
        ->and($completion->missingFieldsForSection('basic-info'))->toBe(['city']);
});

test('participated before false counts as a completed field', function () {
    $attributes = completeProfileAttributes();
    $attributes['participated_before'] = false;

    $completion = ProfileCompletion::for(new Profile($attributes));

    expect($completion->missingFieldsForSection('tournament-experience'))->toBe([])
        ->and($completion->isSectionComplete('tournament-experience'))->toBeTrue();
});

test('profile model delegates completion helpers', function () {
    $profile = new Profile(completeProfileAttributes());

    expect($profile->completionPercentage())->toBe(100)
        ->and($profile->isSectionComplete('contact-info'))->toBeTrue();
});

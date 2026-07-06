<?php

use App\Models\Participant;
use App\Models\Profile;
use App\Models\Tournament;
use App\Models\User;
use App\Support\ParticipantExportRow;

uses(Tests\TestCase::class);

test('participant export row maps registration user and profile fields', function () {
    $user = new User([
        'name' => 'Yousef Al-Shehri',
        'email' => 'driver@example.com',
        'phone' => '0500000001',
    ]);
    $user->id = 10;

    $profile = new Profile([
        'birthdate' => '1998-05-10',
        'whatsapp' => '0500000002',
        'gender' => 'male',
        'city' => 'Aden',
        'skill_level' => 'intermediate',
        'has_ps5' => true,
        'primary_platform' => 'ps5',
        'weekly_hours' => '5_to_10',
        'favorite_games' => ['gt7'],
        'gt7_ranking' => 'top3',
        'toyota_gr_knowledge' => 'knowledgeable',
        'favorite_car' => 'GR Yaris',
        'participated_before' => false,
        'wants_training' => true,
        'join_whatsapp' => false,
        'heard_about' => 'social_media',
        'motivation' => ['challenge'],
        'preferred_time' => 'evening',
        'suggestions' => 'More practice sessions',
        'regular_games' => 'GT7',
    ]);

    $tournament = new Tournament([
        'title' => 'Aden Coastal Qualifier',
    ]);
    $tournament->id = 3;

    $participant = new Participant([
        'location' => 'Aden',
        'created_at' => now()->setDate(2026, 6, 1)->setTime(12, 0),
    ]);
    $participant->id = 55;
    $participant->setRelation('user', $user);
    $participant->setRelation('profile', $profile);
    $participant->setRelation('tournament', $tournament);

    $row = ParticipantExportRow::from($participant)->toArray();

    expect($row['participant_id'])->toBe('55')
        ->and($row['tournament'])->toBe('Aden Coastal Qualifier')
        ->and($row['location'])->toBe('Aden')
        ->and($row['name'])->toBe('Yousef Al-Shehri')
        ->and($row['email'])->toBe('driver@example.com')
        ->and($row['skill_level'])->toBe('Intermediate')
        ->and($row['favorite_games'])->toBe('Gran Turismo 7')
        ->and($row['motivation'])->toBe('Challenge & Competition')
        ->and($row['has_ps5'])->toBe('Yes')
        ->and($row['profile_completion_percent'])->toBeString();
});

test('participant export row includes all expected column keys', function () {
    $labels = ParticipantExportRow::columnLabels();
    $participant = new Participant(['location' => 'Aden']);
    $participant->setRelation('user', new User(['name' => 'Test', 'email' => 'test@example.com']));
    $participant->setRelation('profile', new Profile);
    $participant->setRelation('tournament', new Tournament(['title' => 'Cup']));

    $row = ParticipantExportRow::from($participant)->toArray();

    expect(array_keys($row))->toBe(array_keys($labels));
});

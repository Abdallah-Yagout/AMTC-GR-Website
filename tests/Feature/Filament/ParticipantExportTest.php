<?php

use App\Models\Participant;
use App\Models\Profile;
use App\Models\Tournament;
use App\Models\User;
use App\Support\ParticipantExportRow;
use App\Support\ParticipantExportSupport;

function createTournamentForExportTests(string $title): Tournament
{
    $tournament = Tournament::query()->create([
        'title' => $title,
        'tournament_id' => 1,
        'location' => json_encode(['Aden']),
        'start_date' => '2026-06-01',
        'end_date' => '2026-06-30',
        'image' => 'tournaments/default.jpg',
        'description' => 'Test tournament',
        'status' => 1,
    ]);

    $tournament->update(['tournament_id' => $tournament->id]);

    return $tournament->fresh();
}

test('tournament filter returns only matching participant registrations', function () {
    $user = User::factory()->create();
    $profile = Profile::query()->create([
        'user_id' => $user->id,
        'city' => 'Aden',
        'skill_level' => 'beginner',
        'participated_before' => false,
        'has_ps5' => false,
        'wants_training' => false,
        'join_whatsapp' => false,
    ]);

    $tournamentA = createTournamentForExportTests('Tournament A');
    $tournamentB = createTournamentForExportTests('Tournament B');

    Participant::query()->create([
        'user_id' => $user->id,
        'tournament_id' => $tournamentA->id,
        'location' => 'Aden',
    ]);

    Participant::query()->create([
        'user_id' => $user->id,
        'tournament_id' => $tournamentB->id,
        'location' => 'Sana\'a',
    ]);

    $filtered = Participant::query()
        ->where('tournament_id', $tournamentA->id)
        ->with(['user', 'profile', 'tournament'])
        ->get();

    expect($filtered)->toHaveCount(1)
        ->and($filtered->first()->location)->toBe('Aden')
        ->and($filtered->first()->profile->city)->toBe('Aden');
});

test('participant export row reads persisted registration data', function () {
    $user = User::factory()->create([
        'name' => 'Export Driver',
        'email' => 'export@example.com',
        'phone' => '0500000099',
    ]);

    Profile::query()->create([
        'user_id' => $user->id,
        'gender' => 'male',
        'city' => 'Aden',
        'skill_level' => 'expert',
        'participated_before' => false,
        'has_ps5' => true,
        'wants_training' => false,
        'join_whatsapp' => true,
    ]);

    $tournament = createTournamentForExportTests('Export Cup');

    $participant = Participant::query()->create([
        'user_id' => $user->id,
        'tournament_id' => $tournament->id,
        'location' => 'Aden',
    ]);

    $row = ParticipantExportRow::from($participant->fresh(['user', 'profile', 'tournament']))->toArray();

    expect($row['name'])->toBe('Export Driver')
        ->and($row['email'])->toBe('export@example.com')
        ->and($row['tournament'])->toBe('Export Cup')
        ->and($row['skill_level'])->toBe('Expert');
});

test('tournament filtered participants match export query row count', function () {
    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();

    $tournamentA = createTournamentForExportTests('Tournament A');
    $tournamentB = createTournamentForExportTests('Tournament B');

    Participant::query()->create([
        'user_id' => $userOne->id,
        'tournament_id' => $tournamentA->id,
        'location' => 'Aden',
    ]);

    Participant::query()->create([
        'user_id' => $userOne->id,
        'tournament_id' => $tournamentB->id,
        'location' => 'Sana\'a',
    ]);

    Participant::query()->create([
        'user_id' => $userTwo->id,
        'tournament_id' => $tournamentA->id,
        'location' => 'Aden',
    ]);

    $tournamentAQuery = Participant::query()->where('tournament_id', $tournamentA->id);
    $tournamentBQuery = Participant::query()->where('tournament_id', $tournamentB->id);

    expect($tournamentAQuery->count())->toBe(2)
        ->and($tournamentBQuery->count())->toBe(1);
});

test('export query scopes participants to selected tournament only', function () {
    $tournamentA = createTournamentForExportTests('Tournament A');
    $tournamentB = createTournamentForExportTests('Tournament B');

    $user = User::factory()->create();

    Participant::query()->create([
        'user_id' => $user->id,
        'tournament_id' => $tournamentA->id,
        'location' => 'Aden',
    ]);

    Participant::query()->create([
        'user_id' => $user->id,
        'tournament_id' => $tournamentB->id,
        'location' => 'Sana\'a',
    ]);

    expect(ParticipantExportSupport::queryForTournament($tournamentA->id)->count())->toBe(1)
        ->and(ParticipantExportSupport::queryForTournament($tournamentB->id)->count())->toBe(1);
});

test('export filename slug uses tournament title', function () {
    $tournament = createTournamentForExportTests('Aden Coastal Qualifier');

    expect(ParticipantExportSupport::exportFileNameSlug($tournament->id))
        ->toBe('aden-coastal-qualifier');
});

test('profile registration complete scope matches completion helper', function () {
    $complete = Profile::query()->create([
        'user_id' => User::factory()->create()->id,
        'birthdate' => '1998-05-10',
        'whatsapp' => '0500000001',
        'gender' => 'male',
        'city' => 'Aden',
        'skill_level' => 'intermediate',
        'primary_platform' => 'ps5',
        'weekly_hours' => '5_to_10',
        'favorite_games' => ['gt7'],
        'gt7_ranking' => 'top3',
        'toyota_gr_knowledge' => 'knowledgeable',
        'favorite_car' => 'GR Yaris',
        'participated_before' => false,
        'heard_about' => 'social_media',
        'motivation' => ['challenge'],
        'preferred_time' => 'evening',
        'has_ps5' => true,
        'wants_training' => false,
        'join_whatsapp' => false,
    ]);

    $incomplete = Profile::query()->create([
        'user_id' => User::factory()->create()->id,
        'city' => 'Aden',
        'participated_before' => false,
        'has_ps5' => false,
        'wants_training' => false,
        'join_whatsapp' => false,
    ]);

    expect(Profile::query()->registrationComplete()->pluck('id')->all())
        ->toContain($complete->id)
        ->and(Profile::query()->registrationComplete()->pluck('id')->all())
        ->not->toContain($incomplete->id);
});

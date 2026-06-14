<?php

use App\Models\GameDefinition;
use App\Models\GameUserStat;
use App\Models\User;

/**
 * Minimal winning flip sequence: for each symbol, flip its two indices in order.
 *
 * @param  array<int, int>  $deck
 * @return array<int, int>
 */
function matchingWinningFlipSequence(array $deck): array
{
    $bySymbol = [];
    foreach ($deck as $i => $sym) {
        $bySymbol[$sym][] = $i;
    }
    $seq = [];
    foreach ($bySymbol as $indices) {
        $seq[] = $indices[0];
        $seq[] = $indices[1];
    }

    return $seq;
}

test('guest cannot start matching session', function () {
    $this->postJson(route('games.matching.start'))->assertUnauthorized();
});

test('matching play page is not found when game is disabled', function () {
    GameDefinition::query()->where('slug', 'matching')->update(['is_enabled' => false]);

    $this->get(route('games.matching'))->assertNotFound();
});

test('matching start returns error when game is disabled', function () {
    GameDefinition::query()->where('slug', 'matching')->update(['is_enabled' => false]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('games.matching.start'))
        ->assertStatus(422)
        ->assertJsonPath('error', 'game_disabled');
});

test('matching rejects invalid flip sequence', function () {
    $user = User::factory()->create();
    $start = $this->actingAs($user)->postJson(route('games.matching.start'))->assertOk();
    $sessionId = $start->json('session_id');

    $this->actingAs($user)->postJson(route('games.matching.complete'), [
        'session_id' => $sessionId,
        'flip_sequence' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        'duration_ms' => 5000,
    ])
        ->assertStatus(422)
        ->assertJsonPath('error', 'invalid_sequence');
});

test('matching rejects completion that is too fast', function () {
    $user = User::factory()->create();
    $start = $this->actingAs($user)->postJson(route('games.matching.start'))->assertOk();
    $sessionId = $start->json('session_id');
    $deck = $start->json('deck');
    $seq = matchingWinningFlipSequence($deck);

    $this->actingAs($user)->postJson(route('games.matching.complete'), [
        'session_id' => $sessionId,
        'flip_sequence' => $seq,
        'duration_ms' => 500,
    ])
        ->assertStatus(422)
        ->assertJsonPath('error', 'too_fast');
});

test('matching awards points on verified win then blocks second award during cooldown', function () {
    $user = User::factory()->create();

    $firstStart = $this->actingAs($user)->postJson(route('games.matching.start'))->assertOk();
    $deck1 = $firstStart->json('deck');
    $seq1 = matchingWinningFlipSequence($deck1);

    $complete1 = $this->actingAs($user)->postJson(route('games.matching.complete'), [
        'session_id' => $firstStart->json('session_id'),
        'flip_sequence' => $seq1,
        'duration_ms' => 4000,
    ])->assertOk();

    expect($complete1->json('points_awarded'))->toBe(25);

    $secondStart = $this->actingAs($user)->postJson(route('games.matching.start'))->assertOk();
    $deck2 = $secondStart->json('deck');
    $seq2 = matchingWinningFlipSequence($deck2);

    $complete2 = $this->actingAs($user)->postJson(route('games.matching.complete'), [
        'session_id' => $secondStart->json('session_id'),
        'flip_sequence' => $seq2,
        'duration_ms' => 4000,
    ])->assertOk();

    expect($complete2->json('points_awarded'))->toBe(0)
        ->and($complete2->json('message'))->toBe('cooldown_active');

    expect((int) GameUserStat::query()->where('user_id', $user->id)->value('points'))->toBe(25);
});

test('matching rejects completing the same session twice', function () {
    $user = User::factory()->create();
    $start = $this->actingAs($user)->postJson(route('games.matching.start'))->assertOk();
    $sessionId = $start->json('session_id');
    $deck = $start->json('deck');
    $seq = matchingWinningFlipSequence($deck);

    $this->actingAs($user)->postJson(route('games.matching.complete'), [
        'session_id' => $sessionId,
        'flip_sequence' => $seq,
        'duration_ms' => 4000,
    ])->assertOk();

    $this->actingAs($user)->postJson(route('games.matching.complete'), [
        'session_id' => $sessionId,
        'flip_sequence' => $seq,
        'duration_ms' => 4000,
    ])
        ->assertStatus(422)
        ->assertJsonPath('error', 'session_used');
});

test('games hub lists enabled arcade game with play link', function () {
    $this->get(route('games.index'))
        ->assertOk()
        ->assertSee('Image Matching', false)
        ->assertSee(route('games.matching'), false);
});

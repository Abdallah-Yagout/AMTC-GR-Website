<?php

use App\Support\GamePointsTierProgress;

uses(Tests\TestCase::class);

test('tier track at zero points starts at tier 1 current', function () {
    $t = GamePointsTierProgress::build(0);
    expect($t['current_index'])->toBe(1)
        ->and($t['tiers'][0]['state'])->toBe(GamePointsTierProgress::STATE_CURRENT)
        ->and($t['tiers'][1]['state'])->toBe(GamePointsTierProgress::STATE_LOCKED);
});

test('tier track just below second threshold keeps tier 1 current', function () {
    $t = GamePointsTierProgress::build(74);
    expect($t['current_index'])->toBe(1)
        ->and($t['next_min_points'])->toBe(75)
        ->and($t['points_to_next'])->toBe(1);
});

test('tier track at second threshold moves current to tier 2', function () {
    $t = GamePointsTierProgress::build(75);
    expect($t['current_index'])->toBe(2)
        ->and($t['tiers'][0]['state'])->toBe(GamePointsTierProgress::STATE_COMPLETED)
        ->and($t['tiers'][1]['state'])->toBe(GamePointsTierProgress::STATE_CURRENT);
});

test('tier track at max threshold sets tier 8 current', function () {
    $t = GamePointsTierProgress::build(10000);
    expect($t['current_index'])->toBe(8)
        ->and($t['next_min_points'])->toBeNull()
        ->and($t['points_to_next'])->toBe(0)
        ->and($t['tiers'][7]['state'])->toBe(GamePointsTierProgress::STATE_CURRENT)
        ->and($t['tiers'][6]['state'])->toBe(GamePointsTierProgress::STATE_COMPLETED);
});

test('tier track above max stays at tier 8 current', function () {
    $t = GamePointsTierProgress::build(50000);
    expect($t['current_index'])->toBe(8)
        ->and($t['tiers'][7]['state'])->toBe(GamePointsTierProgress::STATE_CURRENT);
});

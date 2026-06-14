<?php

use App\Models\GamePointSetting;
use App\Models\GameUserStat;
use App\Models\User;
use App\Services\EngagementHubService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    GamePointSetting::singleton();
});

afterEach(function () {
    Carbon::setTestNow();
});

test('check-in rejects second claim same day', function () {
    Carbon::setTestNow(Carbon::parse('2026-04-28 12:00:00', 'UTC'));
    $user = User::factory()->create();
    $svc = app(EngagementHubService::class);

    $r1 = $svc->claimCheckIn($user);
    expect($r1['ok'])->toBeTrue();

    $r2 = $svc->claimCheckIn($user);
    expect($r2['ok'])->toBeFalse()
        ->and($r2['reason'])->toBe('already_claimed');
});

test('check-in continues streak next calendar day', function () {
    Carbon::setTestNow(Carbon::parse('2026-04-28 12:00:00', 'UTC'));
    $user = User::factory()->create();
    $svc = app(EngagementHubService::class);

    $svc->claimCheckIn($user);
    $stat = GameUserStat::query()->where('user_id', $user->id)->first();
    expect((int) $stat->check_in_next_step)->toBe(2);

    Carbon::setTestNow(Carbon::parse('2026-04-29 12:00:00', 'UTC'));
    $r = $svc->claimCheckIn($user);
    expect($r['ok'])->toBeTrue()
        ->and((int) $r['points_awarded'])->toBeGreaterThan(0);

    $stat->refresh();
    expect((int) $stat->check_in_next_step)->toBe(3);
});

test('check-in resets streak after missing a day', function () {
    Carbon::setTestNow(Carbon::parse('2026-04-28 12:00:00', 'UTC'));
    $user = User::factory()->create();
    $svc = app(EngagementHubService::class);
    $svc->claimCheckIn($user);

    Carbon::setTestNow(Carbon::parse('2026-04-30 12:00:00', 'UTC'));
    $r = $svc->claimCheckIn($user);
    expect($r['ok'])->toBeTrue();

    $settings = GamePointSetting::singleton();
    expect((int) $r['points_awarded'])->toBe((int) $settings->check_in_day_1_points);

    $stat = GameUserStat::query()->where('user_id', $user->id)->first();
    expect((int) $stat->check_in_next_step)->toBe(2);
});

test('daily mission awards at most once per day', function () {
    Carbon::setTestNow(Carbon::parse('2026-04-28 12:00:00', 'UTC'));
    $user = User::factory()->create();
    $svc = app(EngagementHubService::class);

    $a = $svc->tryAwardDailyMission($user, EngagementHubService::MISSION_COMMUNITY_UPVOTE);
    $b = $svc->tryAwardDailyMission($user, EngagementHubService::MISSION_COMMUNITY_UPVOTE);

    expect($a)->toBeTrue()
        ->and($b)->toBeFalse();

    $stat = GameUserStat::query()->where('user_id', $user->id)->first();
    $settings = GamePointSetting::singleton();
    expect((int) $stat->points)->toBe((int) $settings->mission_community_rate_points);
});

test('one-time play game mission awards only once', function () {
    $user = User::factory()->create();
    $svc = app(EngagementHubService::class);

    expect($svc->tryAwardOneTimeMission($user, EngagementHubService::MISSION_PLAY_GAME))->toBeTrue();
    expect($svc->tryAwardOneTimeMission($user, EngagementHubService::MISSION_PLAY_GAME))->toBeFalse();
});

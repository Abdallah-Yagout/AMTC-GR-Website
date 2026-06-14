<?php

namespace App\Services;

use App\Models\GamePointSetting;
use App\Models\GameUserStat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EngagementHubService
{
    public const MISSION_COMMUNITY_UPVOTE = 'community_upvote';

    public const MISSION_COMMUNITY_POST = 'community_post';

    public const MISSION_COMMUNITY_REPLY = 'community_reply';

    public const MISSION_PLAY_GAME = 'play_game';

    public const MISSION_JOIN_TOURNAMENT = 'join_tournament';

    /**
     * @return array{ok: bool, reason?: string, points_awarded?: int, total_points?: int, next_step?: int}
     */
    public function claimCheckIn(User $user): array
    {
        return DB::transaction(function () use ($user): array {
            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            $today = Carbon::today();
            $todayStr = $today->toDateString();
            $yesterdayStr = Carbon::yesterday()->toDateString();

            if ($stat->check_in_last_claimed_at && $stat->check_in_last_claimed_at->toDateString() === $todayStr) {
                return ['ok' => false, 'reason' => 'already_claimed'];
            }

            $last = $stat->check_in_last_claimed_at;
            $lastStr = $last?->toDateString();
            $step = max(1, min(7, (int) ($stat->check_in_next_step ?? 1)));

            $settings = GamePointSetting::singleton();
            $amount = 0;

            $continue = $lastStr === null || $lastStr === $yesterdayStr;

            if ($continue) {
                $amount = $this->checkInPointsForStep($settings, $step);
                $step = $step >= 7 ? 1 : $step + 1;
            } else {
                $amount = $this->checkInPointsForStep($settings, 1);
                $step = 2;
            }

            $stat->points = (int) $stat->points + $amount;
            $stat->check_in_last_claimed_at = $today;
            $stat->check_in_next_step = $step;
            $stat->save();

            return [
                'ok' => true,
                'points_awarded' => $amount,
                'total_points' => (int) $stat->points,
                'next_step' => $step,
            ];
        });
    }

    public function tryAwardDailyMission(User $user, string $key): bool
    {
        if (! in_array($key, [
            self::MISSION_COMMUNITY_UPVOTE,
            self::MISSION_COMMUNITY_POST,
            self::MISSION_COMMUNITY_REPLY,
        ], true)) {
            return false;
        }

        return DB::transaction(function () use ($user, $key): bool {
            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            $todayStr = Carbon::today()->toDateString();
            $map = $stat->mission_daily_claims_at ?? [];
            if (! is_array($map)) {
                $map = [];
            }
            if (($map[$key] ?? null) === $todayStr) {
                return false;
            }

            $settings = GamePointSetting::singleton();
            $amount = match ($key) {
                self::MISSION_COMMUNITY_UPVOTE => (int) $settings->mission_community_rate_points,
                self::MISSION_COMMUNITY_POST => (int) $settings->mission_community_post_points,
                self::MISSION_COMMUNITY_REPLY => (int) $settings->mission_community_reply_points,
                default => 0,
            };

            if ($amount <= 0) {
                return false;
            }

            $map[$key] = $todayStr;
            $stat->mission_daily_claims_at = $map;
            $stat->points = (int) $stat->points + $amount;
            $stat->save();

            return true;
        });
    }

    public function tryAwardOneTimeMission(User $user, string $key): bool
    {
        if (! in_array($key, [
            self::MISSION_PLAY_GAME,
            self::MISSION_JOIN_TOURNAMENT,
        ], true)) {
            return false;
        }

        return DB::transaction(function () use ($user, $key): bool {
            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            if ($key === self::MISSION_PLAY_GAME) {
                if ($stat->mission_play_game_awarded_at !== null) {
                    return false;
                }
                $settings = GamePointSetting::singleton();
                $amount = (int) $settings->mission_play_game_points;
                if ($amount <= 0) {
                    return false;
                }
                $stat->points = (int) $stat->points + $amount;
                $stat->mission_play_game_awarded_at = now();
                $stat->save();

                return true;
            }

            if ($stat->mission_join_tournament_awarded_at !== null) {
                return false;
            }
            $settings = GamePointSetting::singleton();
            $amount = (int) $settings->mission_join_tournament_points;
            if ($amount <= 0) {
                return false;
            }
            $stat->points = (int) $stat->points + $amount;
            $stat->mission_join_tournament_awarded_at = now();
            $stat->save();

            return true;
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function hubPayload(User $user): array
    {
        $stat = GameUserStat::query()->where('user_id', $user->id)->first();
        $settings = GamePointSetting::singleton();

        $today = Carbon::today();
        $todayStr = $today->toDateString();
        $yesterdayStr = Carbon::yesterday()->toDateString();

        $last = $stat?->check_in_last_claimed_at;
        $nextStep = max(1, min(7, (int) ($stat?->check_in_next_step ?? 1)));

        $claimedToday = $last && $last->toDateString() === $todayStr;
        $lastStr = $last?->toDateString();
        $streakBroken = $last && $lastStr !== $todayStr && $lastStr !== $yesterdayStr;
        $passedSlots = ($streakBroken || $last === null) ? 0 : max(0, $nextStep - 1);
        $highlightStep = $streakBroken || $last === null ? 1 : $nextStep;

        $dayPoints = [];
        for ($i = 1; $i <= 7; $i++) {
            $dayPoints[$i] = $this->checkInPointsForStep($settings, $i);
        }

        $missionMap = $stat?->mission_daily_claims_at ?? [];
        if (! is_array($missionMap)) {
            $missionMap = [];
        }

        return [
            'check_in' => [
                'claimed_today' => $claimedToday,
                'next_step' => $nextStep,
                'highlight_step' => $highlightStep,
                'passed_slots' => $passedSlots,
                'streak_broken' => $streakBroken,
                'day_points' => $dayPoints,
            ],
            'missions' => [
                [
                    'key' => self::MISSION_COMMUNITY_UPVOTE,
                    'label' => __('Rate in community'),
                    'hint' => __('Daily'),
                    'points' => (int) $settings->mission_community_rate_points,
                    'done_today' => ($missionMap[self::MISSION_COMMUNITY_UPVOTE] ?? null) === $todayStr,
                    'href' => route('forum.index'),
                ],
                [
                    'key' => self::MISSION_COMMUNITY_POST,
                    'label' => __('Post in community'),
                    'hint' => __('Daily'),
                    'points' => (int) $settings->mission_community_post_points,
                    'done_today' => ($missionMap[self::MISSION_COMMUNITY_POST] ?? null) === $todayStr,
                    'href' => route('forum.index'),
                ],
                [
                    'key' => self::MISSION_COMMUNITY_REPLY,
                    'label' => __('Reply in community'),
                    'hint' => __('Daily'),
                    'points' => (int) $settings->mission_community_reply_points,
                    'done_today' => ($missionMap[self::MISSION_COMMUNITY_REPLY] ?? null) === $todayStr,
                    'href' => route('forum.index'),
                ],
                [
                    'key' => self::MISSION_PLAY_GAME,
                    'label' => __('Play a game'),
                    'hint' => __('One-time'),
                    'points' => (int) $settings->mission_play_game_points,
                    'done_today' => $stat?->mission_play_game_awarded_at !== null,
                    'href' => route('games.index'),
                ],
                [
                    'key' => self::MISSION_JOIN_TOURNAMENT,
                    'label' => __('Join a tournament'),
                    'hint' => __('One-time'),
                    'points' => (int) $settings->mission_join_tournament_points,
                    'done_today' => $stat?->mission_join_tournament_awarded_at !== null,
                    'href' => route('tournament.index'),
                ],
            ],
        ];
    }

    private function checkInPointsForStep(GamePointSetting $settings, int $step): int
    {
        return match ($step) {
            1 => (int) $settings->check_in_day_1_points,
            2 => (int) $settings->check_in_day_2_points,
            3 => (int) $settings->check_in_day_3_points,
            4 => (int) $settings->check_in_day_4_points,
            5 => (int) $settings->check_in_day_5_points,
            6 => (int) $settings->check_in_day_6_points,
            7 => (int) $settings->check_in_day_7_points,
            default => 0,
        };
    }
}

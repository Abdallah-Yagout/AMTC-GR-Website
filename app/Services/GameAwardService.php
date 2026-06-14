<?php

namespace App\Services;

use App\Games\Matching\MatchingGameEngine;
use App\Models\GameDefinition;
use App\Models\GameSession;
use App\Models\GameUserAward;
use App\Models\GameUserStat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GameAwardService
{
    public const MATCHING_SLUG = 'matching';

    private const MIN_DURATION_MS = 2500;

    private const SESSION_TTL_MINUTES = 30;

    public function __construct(
        private MatchingGameEngine $matchingEngine
    ) {}

    /**
     * Points cooldown window for matching (for API + UI).
     *
     * @return array{points_cooldown_hours: int, next_points_at: ?string, can_earn_points: bool}
     */
    private function matchingPointsCooldownMeta(User $user, GameDefinition $definition): array
    {
        $cooldownHours = max(1, (int) $definition->points_cooldown_hours);
        if ($definition->reward_type !== GameDefinition::REWARD_POINTS || (int) $definition->points_per_completion <= 0) {
            return [
                'points_cooldown_hours' => $cooldownHours,
                'next_points_at' => null,
                'can_earn_points' => false,
            ];
        }

        $award = GameUserAward::query()
            ->where('user_id', $user->id)
            ->where('game_slug', self::MATCHING_SLUG)
            ->first();

        if (! $award || $award->last_points_awarded_at === null) {
            return [
                'points_cooldown_hours' => $cooldownHours,
                'next_points_at' => null,
                'can_earn_points' => true,
            ];
        }

        $next = $award->last_points_awarded_at->copy()->addHours($cooldownHours);
        if ($next->lte(Carbon::now())) {
            return [
                'points_cooldown_hours' => $cooldownHours,
                'next_points_at' => null,
                'can_earn_points' => true,
            ];
        }

        return [
            'points_cooldown_hours' => $cooldownHours,
            'next_points_at' => $next->toIso8601String(),
            'can_earn_points' => false,
        ];
    }

    /**
     * @return array{ok: bool, error?: string, session_id?: int, deck?: array<int, int>, pair_count?: int, points_per_win?: int, cooldown_hours?: int, next_points_at?: ?string, can_earn_points?: bool, points_cooldown_hours?: int}
     */
    public function startMatchingSession(User $user): array
    {
        $definition = GameDefinition::query()
            ->where('slug', self::MATCHING_SLUG)
            ->where('is_enabled', true)
            ->first();

        if (! $definition) {
            return ['ok' => false, 'error' => 'game_disabled'];
        }

        $pairCount = $definition->matchingPairCount();
        $deck = $this->matchingEngine->buildShuffledDeck($pairCount);

        GameSession::query()
            ->where('user_id', $user->id)
            ->where('game_slug', self::MATCHING_SLUG)
            ->where('state', GameSession::STATE_PENDING)
            ->update(['state' => GameSession::STATE_EXPIRED]);

        $session = GameSession::query()->create([
            'user_id' => $user->id,
            'game_slug' => self::MATCHING_SLUG,
            'state' => GameSession::STATE_PENDING,
            'payload' => [
                'deck' => $deck,
                'pair_count' => $pairCount,
            ],
            'expires_at' => now()->addMinutes(self::SESSION_TTL_MINUTES),
        ]);

        $cooldownMeta = $this->matchingPointsCooldownMeta($user, $definition);

        return [
            'ok' => true,
            'session_id' => $session->id,
            'deck' => $deck,
            'pair_count' => $pairCount,
            'points_per_win' => (int) $definition->points_per_completion,
            'cooldown_hours' => (int) $definition->points_cooldown_hours,
            'next_points_at' => $cooldownMeta['next_points_at'],
            'can_earn_points' => $cooldownMeta['can_earn_points'],
            'points_cooldown_hours' => $cooldownMeta['points_cooldown_hours'],
        ];
    }

    /**
     * @param  array<int, int>  $flipSequence
     * @return array{ok: bool, error?: string, points_awarded?: int, total_points?: int, message?: string, win?: bool, next_points_at?: ?string, can_earn_points?: bool, points_cooldown_hours?: int}
     */
    public function completeMatchingSession(User $user, int $sessionId, array $flipSequence, int $durationMs): array
    {
        $definition = GameDefinition::query()
            ->where('slug', self::MATCHING_SLUG)
            ->where('is_enabled', true)
            ->first();

        if (! $definition) {
            return ['ok' => false, 'error' => 'game_disabled'];
        }

        $pairCount = $definition->matchingPairCount();

        if (count($flipSequence) < $this->matchingEngine->minFlipsForWin($pairCount)) {
            return ['ok' => false, 'error' => 'invalid_sequence'];
        }

        if (count($flipSequence) > $this->matchingEngine->maxFlipsAllowed($pairCount)) {
            return ['ok' => false, 'error' => 'invalid_sequence'];
        }

        if ($durationMs < self::MIN_DURATION_MS) {
            return ['ok' => false, 'error' => 'too_fast'];
        }

        return DB::transaction(function () use ($user, $sessionId, $flipSequence, $definition, $pairCount): array {
            /** @var GameSession|null $session */
            $session = GameSession::query()
                ->whereKey($sessionId)
                ->lockForUpdate()
                ->first();

            if (! $session || $session->user_id !== $user->id || $session->game_slug !== self::MATCHING_SLUG) {
                return ['ok' => false, 'error' => 'invalid_session'];
            }

            if (! $session->isPending()) {
                return ['ok' => false, 'error' => 'session_used'];
            }

            if ($session->expires_at->isPast()) {
                $session->state = GameSession::STATE_EXPIRED;
                $session->save();

                return ['ok' => false, 'error' => 'session_expired'];
            }

            $deck = $session->payload['deck'] ?? null;
            if (! is_array($deck) || count($deck) !== 2 * $pairCount) {
                return ['ok' => false, 'error' => 'invalid_session'];
            }

            $deckInts = array_map(static fn ($v) => (int) $v, $deck);
            $flips = array_map(static fn ($v) => (int) $v, $flipSequence);

            if (! $this->matchingEngine->isWinningSequence($deckInts, $flips)) {
                return ['ok' => false, 'error' => 'invalid_sequence'];
            }

            $session->state = GameSession::STATE_COMPLETED;
            $session->save();

            $points = (int) $definition->points_per_completion;
            $cooldownHours = max(1, (int) $definition->points_cooldown_hours);

            if ($definition->reward_type !== GameDefinition::REWARD_POINTS || $points <= 0) {
                $meta = $this->matchingPointsCooldownMeta($user, $definition);

                return [
                    'ok' => true,
                    'win' => true,
                    'points_awarded' => 0,
                    'total_points' => GameUserStat::query()->where('user_id', $user->id)->value('points') ?? 0,
                    'message' => 'no_points_configured',
                    'next_points_at' => $meta['next_points_at'],
                    'can_earn_points' => $meta['can_earn_points'],
                    'points_cooldown_hours' => $meta['points_cooldown_hours'],
                ];
            }

            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            $award = GameUserAward::query()->lockForUpdate()->firstOrCreate(
                [
                    'user_id' => $user->id,
                    'game_slug' => self::MATCHING_SLUG,
                ],
                ['last_points_awarded_at' => null]
            );

            $now = Carbon::now();
            $canAward = $award->last_points_awarded_at === null
                || $award->last_points_awarded_at->copy()->addHours($cooldownHours)->lte($now);

            if (! $canAward) {
                $nextPointsAt = $award->last_points_awarded_at->copy()->addHours($cooldownHours);

                return [
                    'ok' => true,
                    'win' => true,
                    'points_awarded' => 0,
                    'total_points' => (int) $stat->points,
                    'message' => 'cooldown_active',
                    'next_points_at' => $nextPointsAt->toIso8601String(),
                    'can_earn_points' => false,
                    'points_cooldown_hours' => $cooldownHours,
                ];
            }

            $stat->points = (int) $stat->points + $points;
            $stat->save();

            $award->last_points_awarded_at = $now;
            $award->save();

            $nextPointsAt = $now->copy()->addHours($cooldownHours);

            return [
                'ok' => true,
                'win' => true,
                'points_awarded' => $points,
                'total_points' => (int) $stat->points,
                'message' => 'points_awarded',
                'next_points_at' => $nextPointsAt->toIso8601String(),
                'can_earn_points' => false,
                'points_cooldown_hours' => $cooldownHours,
            ];
        });
    }
}

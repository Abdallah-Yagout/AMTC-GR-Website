<?php

namespace App\Support;

use App\Models\GamePointSetting;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class GamePointsTierProgress
{
    public const STATE_LOCKED = 'locked';

    public const STATE_COMPLETED = 'completed';

    public const STATE_CURRENT = 'current';

    /**
     * @return array{
     *     points: int,
     *     current_index: int,
     *     tiers: list<array{index: int, min_points: int, label: string, badge: string, state: string}>,
     *     next_min_points: ?int,
     *     points_to_next: int,
     *     progress_to_next: float
     * }
     */
    public static function build(int $gamePoints): array
    {
        $gamePoints = max(0, $gamePoints);
        $raw = self::rawTiersDefinition();
        $n = count($raw);
        if ($n !== 8) {
            throw new InvalidArgumentException('Reward tiers must contain exactly 8 entries.');
        }

        $tiers = [];
        $prevMin = -1;
        foreach ($raw as $i => $row) {
            $min = (int) ($row['min_points'] ?? -1);
            if ($i > 0 && $min <= $prevMin) {
                throw new InvalidArgumentException('Tiers must have strictly increasing min_points.');
            }
            if ($i === 0 && $min !== 0) {
                throw new InvalidArgumentException('First tier min_points must be 0.');
            }
            $prevMin = $min;
            $tiers[] = [
                'index' => $i + 1,
                'min_points' => $min,
                'label' => (string) ($row['label'] ?? 'Tier '.($i + 1)),
                'badge' => (string) ($row['badge'] ?? 'bronze'),
            ];
        }

        $nextMinByTier = [];
        for ($i = 0; $i < $n; $i++) {
            $nextMinByTier[$i] = $i < $n - 1 ? $tiers[$i + 1]['min_points'] : PHP_INT_MAX;
        }

        $currentIndex0 = $n - 1;
        for ($i = 0; $i < $n; $i++) {
            if ($gamePoints < $nextMinByTier[$i]) {
                $currentIndex0 = $i;
                break;
            }
        }

        $enriched = [];
        for ($i = 0; $i < $n; $i++) {
            if ($gamePoints < $tiers[$i]['min_points']) {
                $state = self::STATE_LOCKED;
            } elseif ($i === $currentIndex0) {
                $state = self::STATE_CURRENT;
            } else {
                $state = self::STATE_COMPLETED;
            }
            $enriched[] = array_merge($tiers[$i], ['state' => $state]);
        }

        $nextMin = $nextMinByTier[$currentIndex0];
        $pointsToNext = $nextMin === PHP_INT_MAX ? 0 : max(0, $nextMin - $gamePoints);
        $span = $nextMin === PHP_INT_MAX
            ? 1.0
            : max(1, $nextMin - $tiers[$currentIndex0]['min_points']);
        $progressOrigin = $tiers[$currentIndex0]['min_points'];
        $progressToNext = $nextMin === PHP_INT_MAX
            ? 1.0
            : min(1.0, max(0.0, ($gamePoints - $progressOrigin) / $span));

        return [
            'points' => $gamePoints,
            'current_index' => $currentIndex0 + 1,
            'tiers' => $enriched,
            'next_min_points' => $nextMin === PHP_INT_MAX ? null : $nextMin,
            'points_to_next' => $pointsToNext,
            'progress_to_next' => $progressToNext,
        ];
    }

    /**
     * Admin DB override when set; otherwise config. Skips DB when table/column missing (e.g. unit tests).
     *
     * @return list<array{min_points?: int, label?: string, badge?: string}>
     */
    public static function rawTiersDefinition(): array
    {
        if (! Schema::hasTable('game_point_settings') || ! Schema::hasColumn('game_point_settings', 'reward_tiers')) {
            return config('game_reward_tiers.tiers', []);
        }

        $row = GamePointSetting::query()->find(1);
        if ($row !== null) {
            $custom = $row->reward_tiers;
            if (is_array($custom) && count($custom) === 8) {
                return $custom;
            }
        }

        return config('game_reward_tiers.tiers', []);
    }

    /**
     * @param  array<int, mixed>  $rows
     */
    public static function assertValidTierRows(array $rows): void
    {
        if (count($rows) !== 8) {
            throw new InvalidArgumentException(__('Exactly 8 reward tiers are required.'));
        }

        $prevMin = -1;
        foreach ($rows as $i => $row) {
            if (! is_array($row)) {
                throw new InvalidArgumentException(__('Each tier must be a valid row.'));
            }
            $min = (int) ($row['min_points'] ?? -1);
            if ($i > 0 && $min <= $prevMin) {
                throw new InvalidArgumentException(__('Minimum points must be strictly higher than the previous tier (tier :n).', ['n' => $i + 1]));
            }
            if ($i === 0 && $min !== 0) {
                throw new InvalidArgumentException(__('The first tier must have minimum points set to 0.'));
            }
            $prevMin = $min;
            $label = trim((string) ($row['label'] ?? ''));
            if ($label === '') {
                throw new InvalidArgumentException(__('Each tier needs a label (tier :n).', ['n' => $i + 1]));
            }
            $badge = (string) ($row['badge'] ?? '');
            $allowed = array_keys(config('game_reward_tiers.badge_options', []));
            if ($allowed !== [] && ! in_array($badge, $allowed, true)) {
                throw new InvalidArgumentException(__('Invalid badge for tier :n.', ['n' => $i + 1]));
            }
        }
    }
}

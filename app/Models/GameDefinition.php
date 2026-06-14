<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameDefinition extends Model
{
    public const REWARD_POINTS = 'points';

    public const REWARD_HIGH_SCORE = 'high_score';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_enabled',
        'sort_order',
        'reward_type',
        'points_per_completion',
        'points_cooldown_hours',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
            'points_per_completion' => 'integer',
            'points_cooldown_hours' => 'integer',
            'config' => 'array',
        ];
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /** @see public/images/games/matching/gr-1.png … gr-10.png */
    public const MATCHING_MAX_PAIR_COUNT = 10;

    public function matchingPairCount(): int
    {
        $n = (int) ($this->config['pair_count'] ?? 8);

        return max(4, min(self::MATCHING_MAX_PAIR_COUNT, $n));
    }

    public function playUrl(): ?string
    {
        return match ($this->slug) {
            'matching' => route('games.matching'),
            default => null,
        };
    }

    /** Promo / cover image for the Arcade hub card (not card backs in-game). */
    public function hubCoverImageUrl(): ?string
    {
        return match ($this->slug) {
            'matching' => asset('images/games/matching/mg-cover.png'),
            default => null,
        };
    }
}

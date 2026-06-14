<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePointSetting extends Model
{
    protected $fillable = [
        'profile_completed_points',
        'image_uploaded_points',
        'daily_claim_points',
        'reward_tiers',
        'check_in_day_1_points',
        'check_in_day_2_points',
        'check_in_day_3_points',
        'check_in_day_4_points',
        'check_in_day_5_points',
        'check_in_day_6_points',
        'check_in_day_7_points',
        'mission_community_rate_points',
        'mission_community_post_points',
        'mission_community_reply_points',
        'mission_play_game_points',
        'mission_join_tournament_points',
    ];

    protected function casts(): array
    {
        return [
            'reward_tiers' => 'array',
        ];
    }

    public static function singleton(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'profile_completed_points' => 200,
                'image_uploaded_points' => 40,
                'daily_claim_points' => 10,
            ]
        );
    }

    /**
     * @return list<array{min_points: int, label: string, badge: string}>
     */
    public function rewardTiersOrConfigDefault(): array
    {
        $custom = $this->reward_tiers;
        if (is_array($custom) && count($custom) === 8) {
            return $custom;
        }

        /** @var list<array{min_points: int, label: string, badge: string}> */
        return config('game_reward_tiers.tiers', []);
    }
}

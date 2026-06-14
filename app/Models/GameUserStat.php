<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameUserStat extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'profile_completed_awarded_at',
        'image_uploaded_awarded_at',
        'last_daily_claim_date',
        'check_in_last_claimed_at',
        'check_in_next_step',
        'mission_daily_claims_at',
        'mission_play_game_awarded_at',
        'mission_join_tournament_awarded_at',
    ];

    protected function casts(): array
    {
        return [
            'profile_completed_awarded_at' => 'datetime',
            'image_uploaded_awarded_at' => 'datetime',
            'last_daily_claim_date' => 'date',
            'check_in_last_claimed_at' => 'date',
            'check_in_next_step' => 'integer',
            'mission_daily_claims_at' => 'array',
            'mission_play_game_awarded_at' => 'datetime',
            'mission_join_tournament_awarded_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

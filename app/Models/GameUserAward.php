<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameUserAward extends Model
{
    protected $fillable = [
        'user_id',
        'game_slug',
        'last_points_awarded_at',
    ];

    protected function casts(): array
    {
        return [
            'last_points_awarded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

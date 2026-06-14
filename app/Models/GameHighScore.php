<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameHighScore extends Model
{
    protected $fillable = [
        'user_id',
        'game_slug',
        'score',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

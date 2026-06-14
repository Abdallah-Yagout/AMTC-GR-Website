<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    public const STATE_PENDING = 'pending';

    public const STATE_COMPLETED = 'completed';

    public const STATE_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'game_slug',
        'state',
        'payload',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->state === self::STATE_PENDING;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'tournament_id',
        'location',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function leaderboards()
    {
        return $this->belongsToMany(Leaderboard::class)
            ->withPivot(['position', 'time_taken', 'status'])
            ->withTimestamps();
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'user_id');
    }

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }

    public function leaderboardEntries()
    {
        return $this->hasMany(Leaderboard::class, 'user_id', 'user_id')
            ->where('tournament_id', $this->tournament_id)
            ->where('location', $this->location);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithTournamentAndResults(Builder $query): Builder
    {
        return $query->with([
            'tournament',
            'leaderboards.tournament',
        ]);
    }

    public function hasPublishedResult(): bool
    {
        return $this->leaderboards->contains(function ($leaderboard): bool {
            $pivot = $leaderboard->pivot;

            return ! is_null($pivot?->position)
                || ! is_null($pivot?->time_taken)
                || ! is_null($pivot?->status);
        });
    }
}

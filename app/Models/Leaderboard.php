<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $fillable = [
        'tournament_id',
        'user_id',
        'location',
        'time_taken',
        'position',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

}

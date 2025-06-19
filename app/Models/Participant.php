<?php

namespace App\Models;

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

    public function leaderboard()
    {

        return $this->hasOne('App\Models\Leaderboard','user_id','user_id');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile','user_id');
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
}

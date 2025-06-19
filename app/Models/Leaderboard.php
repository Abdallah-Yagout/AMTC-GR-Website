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

    // app/Models/Leaderboard.php

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'user_id', 'user_id')
            ->where('tournament_id', $this->tournament_id)
            ->where('location', $this->location);
    }
    // app/Models/Leaderboard.php
    public function participants()
    {
        return $this->belongsToMany(Participant::class)
            ->withPivot(['position', 'time_taken', 'status'])
            ->withTimestamps();
    }
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }



    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

}

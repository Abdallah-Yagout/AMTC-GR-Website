<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tournament extends Model
{
    protected $casts = [
        'location' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }


    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

}

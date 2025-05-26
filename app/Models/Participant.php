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

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile','user_id');
    }
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }
}

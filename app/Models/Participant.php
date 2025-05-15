<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'upvoteable_type',
        'upvoteable_id',
        'user_id',
    ];
}

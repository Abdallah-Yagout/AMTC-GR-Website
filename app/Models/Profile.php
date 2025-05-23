<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected function casts(): array
    {
        return [
            'favorite_games' => 'array',
            'motivation' => 'array',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

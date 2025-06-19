<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'birthdate',
        'user_id',
        'whatsapp',
        'gender',
        'city',
        'toyota_gr_knowledge',
        'favorite_car',
        'skill_level',
        'has_ps5',
        'primary_platform',
        'weekly_hours',
        'favorite_games',
        'gt7_ranking',
        'heard_about',
        'motivation',
        'preferred_time',
        'regular_games',
        'suggestions',
        'participated_before',
        'wants_training',
        'join_whatsapp',
    ];
    protected function casts(): array
    {
        return [
            'favorite_games' => 'array',
            'motivation' => 'array',
            'has_ps5' => 'boolean',
            'participated_before' => 'boolean',
            'wants_training' => 'boolean',
            'join_whatsapp' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

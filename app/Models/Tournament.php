<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $casts = [
        'location' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function participants()
    {
        return $this->hasMany(\App\Models\participant::class);
    }

}

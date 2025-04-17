<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Forum extends Model
{
    use HasTranslations;
    public $translatable = ['title','body']; // translatable attributes


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

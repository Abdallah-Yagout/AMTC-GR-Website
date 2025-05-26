<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'status',
    ];
    use HasTranslations;
    public $translatable = ['title','description']; // translatable attributes

}

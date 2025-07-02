<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;


class Forum extends Model
{
    protected $fillable=['image','title','body','slug','user_id'];
//    use HasTranslations;
//    public $translatable = ['title','body']; // translatable attributes
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Generate slug only if title is changed or slug is empty

            if ($model->isDirty('title') || empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }

        });
    }

    public function upvotes()
    {
        return $this->morphMany(Vote::class, 'upvoteable');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}

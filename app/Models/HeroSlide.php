<?php

namespace App\Models;

use App\Support\GrStockImage;
use App\Support\PublicStorageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class HeroSlide extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'subtitle', 'cta_text'];

    protected $fillable = [
        'title',
        'subtitle',
        'cta_text',
        'cta_url',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->latest();
    }

    public function getImageUrlAttribute(): string
    {
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (filled($this->image)) {
            return PublicStorageUrl::url($this->image);
        }

        return GrStockImage::stockUrl('hero-slide-'.($this->id ?? 'fallback'));
    }
}

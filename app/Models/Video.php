<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'source_type',
        'video_url',
        'video_file',
        'thumbnail',
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

    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->source_type !== 'external' || ! $this->video_url) {
            return null;
        }

        return $this->toEmbedUrl($this->video_url);
    }

    public function getPlaybackUrlAttribute(): ?string
    {
        if ($this->source_type !== 'upload' || ! $this->video_file) {
            return null;
        }

        return $this->toStorageUrl($this->video_file);
    }

    public function getPreviewImageAttribute(): ?string
    {
        if ($this->thumbnail) {
            return $this->toStorageUrl($this->thumbnail);
        }

        if ($this->source_type !== 'external' || ! $this->video_url) {
            return null;
        }

        $youtubeId = $this->extractYoutubeId($this->video_url);
        if ($youtubeId) {
            return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
        }

        return null;
    }

    protected function toStorageUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }

    protected function toEmbedUrl(string $url): string
    {
        $youtubeId = $this->extractYoutubeId($url);
        if ($youtubeId) {
            return "https://www.youtube.com/embed/{$youtubeId}?rel=0";
        }

        $vimeoId = $this->extractVimeoId($url);
        if ($vimeoId) {
            return "https://player.vimeo.com/video/{$vimeoId}";
        }

        return $url;
    }

    protected function extractYoutubeId(string $url): ?string
    {
        $pattern = '%(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})%i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function extractVimeoId(string $url): ?string
    {
        $pattern = '%vimeo\.com/(?:video/)?([0-9]+)%i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

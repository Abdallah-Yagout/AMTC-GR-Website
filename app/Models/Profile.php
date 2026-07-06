<?php

namespace App\Models;

use App\Support\ProfileCompletion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public const REQUIRED_COMPLETION_FIELDS = [
        'birthdate',
        'whatsapp',
        'gender',
        'city',
        'skill_level',
        'primary_platform',
        'weekly_hours',
        'favorite_games',
        'gt7_ranking',
        'toyota_gr_knowledge',
        'favorite_car',
        'participated_before',
        'heard_about',
        'motivation',
        'preferred_time',
    ];

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

    public function missingCompletionFields(): array
    {
        $missing = [];

        foreach (self::REQUIRED_COMPLETION_FIELDS as $field) {
            if ($this->isEmptyForCompletion($this->{$field} ?? null)) {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    public function isCompleteForRace(): bool
    {
        return count($this->missingCompletionFields()) === 0;
    }

    public function completionPercentage(): int
    {
        return ProfileCompletion::for($this)->percentage();
    }

    public function isSectionComplete(string $section): bool
    {
        return ProfileCompletion::for($this)->isSectionComplete($section);
    }

    public function scopeRegistrationComplete(Builder $query): Builder
    {
        foreach (self::REQUIRED_COMPLETION_FIELDS as $field) {
            if (in_array($field, ['participated_before', 'has_ps5', 'wants_training', 'join_whatsapp'], true)) {
                $query->whereNotNull($field);

                continue;
            }

            if (in_array($field, ['favorite_games', 'motivation'], true)) {
                $query->whereNotNull($field)
                    ->where($field, '!=', '')
                    ->where($field, '!=', '[]');

                continue;
            }

            $query->whereNotNull($field)->where($field, '!=', '');
        }

        return $query;
    }

    public function scopeRegistrationIncomplete(Builder $query): Builder
    {
        return $query->whereNot(fn (Builder $inner): Builder => $inner->registrationComplete());
    }

    protected function isEmptyForCompletion(mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '' || trim($value) === '[]';
        }

        if (is_array($value)) {
            return count($value) === 0;
        }

        return false;
    }
}

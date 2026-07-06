<?php

namespace App\Support;

use App\Helpers\Location;

final class ProfileFormOptions
{
    /** @var array<string, array<string, string>> */
    private const OPTIONS = [
        'gender' => [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ],
        'skill_level' => [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'expert' => 'Expert',
        ],
        'primary_platform' => [
            'ps5' => 'PlayStation 5',
            'ps4' => 'PlayStation 4',
            'pc' => 'PC',
            'other' => 'Other Platform',
        ],
        'weekly_hours' => [
            'less_than_5' => 'Less than 5 hours',
            '5_to_10' => '5 to 10 hours',
            'more_than_10' => 'More than 10 hours',
            '10-15' => '10 to 15 hours',
        ],
        'favorite_games' => [
            'fifa' => 'FIFA (FC25)',
            'pes' => 'PES',
            'gt7' => 'Gran Turismo 7',
            'gran_turismo_7' => 'Gran Turismo 7',
            'cod' => 'Call of Duty',
            'fortnite' => 'Fortnite',
            'apex' => 'Apex Legends',
            'minecraft' => 'Minecraft',
            'gta' => 'GTA V',
        ],
        'gt7_ranking' => [
            'top1' => 'My favorite game',
            'top3' => 'In my top 3 games',
            'top5' => 'In my top 5 games',
            'lower' => 'Lower than that',
        ],
        'toyota_gr_knowledge' => [
            'expert' => 'I know them well and follow their news',
            'knowledgeable' => 'I have some knowledge about them',
            'heard' => 'I\'ve only heard of them',
            'unknown' => 'I don\'t know them at all',
            'intermediate' => 'Intermediate',
        ],
        'heard_about' => [
            'social_media' => 'Social Media',
            'friends' => 'Friends & Family',
            'gaming_cafes' => 'Gaming Cafes',
            'websites' => 'Websites',
        ],
        'motivation' => [
            'prizes' => 'Prizes',
            'love_cars' => 'Love of cars/driving',
            'challenge' => 'Challenge & Competition',
            'toyota_experience' => 'Toyota GR experience',
            'skill_development' => 'Skill development',
            'competition' => 'Competition',
        ],
        'preferred_time' => [
            'afternoon' => 'Afternoon',
            'evening' => 'Evening',
            'weekend' => 'Weekends only',
            'flexible' => 'Flexible (any time)',
        ],
    ];

    /** @return array<string, string> */
    public static function for(string $field): array
    {
        if ($field === 'city') {
            return Location::cities();
        }

        return self::OPTIONS[$field] ?? [];
    }

    public static function labelFor(string $field, mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $options = self::for($field);

        if (is_bool($value)) {
            return self::formatBoolean($value);
        }

        return $options[(string) $value] ?? (string) $value;
    }

    /** @param  array<int, string>|null  $values */
    public static function formatList(?array $values, string $field): string
    {
        if (empty($values)) {
            return '';
        }

        $options = self::for($field);

        return collect($values)
            ->map(fn ($value) => $options[(string) $value] ?? (string) $value)
            ->implode(', ');
    }

    public static function formatBoolean(?bool $value): string
    {
        if ($value === null) {
            return '';
        }

        return $value ? 'Yes' : 'No';
    }
}

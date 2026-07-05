<?php

namespace App\Support;

use App\Models\Profile;

final class ProfileCompletion
{
    /** @var array<string, list<string>> */
    public const SECTIONS = [
        'basic-info' => ['birthdate', 'gender', 'city'],
        'contact-info' => ['whatsapp'],
        'gaming-experience' => ['skill_level', 'primary_platform', 'weekly_hours'],
        'game-preferences' => ['favorite_games', 'gt7_ranking'],
        'toyota-g-r-knowledge' => ['toyota_gr_knowledge', 'favorite_car'],
        'tournament-experience' => ['participated_before'],
        'additional-information' => ['heard_about', 'motivation', 'preferred_time'],
    ];

    /** @var list<string> */
    private array $missingFields;

    public function __construct(
        private readonly ?Profile $profile,
    ) {
        $this->missingFields = $this->profile?->missingCompletionFields() ?? Profile::REQUIRED_COMPLETION_FIELDS;
    }

    public static function for(?Profile $profile): self
    {
        return new self($profile);
    }

    public function percentage(): int
    {
        $total = $this->totalCount();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->completedCount() / $total) * 100);
    }

    public function completedCount(): int
    {
        return $this->totalCount() - count($this->missingFields);
    }

    public function totalCount(): int
    {
        return count(Profile::REQUIRED_COMPLETION_FIELDS);
    }

    public function remainingCount(): int
    {
        return count($this->missingFields);
    }

    public function isComplete(): bool
    {
        return $this->remainingCount() === 0;
    }

    public function isSectionComplete(string $sectionKey): bool
    {
        return count($this->missingFieldsForSection($sectionKey)) === 0;
    }

    /**
     * @return list<string>
     */
    public function missingFieldsForSection(string $sectionKey): array
    {
        $fields = self::SECTIONS[$sectionKey] ?? [];

        return array_values(array_intersect($this->missingFields, $fields));
    }

    /**
     * @return list<string>
     */
    public function missingFields(): array
    {
        return $this->missingFields;
    }
}

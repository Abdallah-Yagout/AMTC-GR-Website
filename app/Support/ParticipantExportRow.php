<?php

namespace App\Support;

use App\Models\Participant;
use Illuminate\Support\Carbon;

final class ParticipantExportRow
{
    /** @var array<string, string> */
    private const COLUMN_LABELS = [
        'participant_id' => 'Participant ID',
        'registered_at' => 'Registered At',
        'tournament' => 'Tournament',
        'location' => 'Location',
        'name' => 'Full Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'birthdate' => 'Date of Birth',
        'whatsapp' => 'WhatsApp',
        'gender' => 'Gender',
        'city' => 'City',
        'skill_level' => 'Skill Level',
        'has_ps5' => 'Owns PS5',
        'primary_platform' => 'Primary Platform',
        'weekly_hours' => 'Weekly Play Hours',
        'favorite_games' => 'Favorite Games',
        'gt7_ranking' => 'GT7 Ranking',
        'toyota_gr_knowledge' => 'Toyota GR Knowledge',
        'favorite_car' => 'Favorite Car',
        'participated_before' => 'Participated in AMTC 2024',
        'wants_training' => 'Wants Training',
        'join_whatsapp' => 'Join WhatsApp Channel',
        'heard_about' => 'Heard About Tournament',
        'motivation' => 'Motivation',
        'preferred_time' => 'Preferred Time',
        'suggestions' => 'Suggestions',
        'regular_games' => 'Regular Games',
        'profile_completion_percent' => 'Profile Completion %',
    ];

    public static function from(Participant $participant): self
    {
        $participant->loadMissing(['user', 'profile', 'tournament']);

        return new self($participant);
    }

    public function __construct(
        private readonly Participant $participant,
    ) {}

    /** @return array<string, string> */
    public function toArray(): array
    {
        $user = $this->participant->user;
        $profile = $this->participant->profile;
        $tournament = $this->participant->tournament;

        $favoriteGames = $profile?->favorite_games;
        if (is_string($favoriteGames)) {
            $favoriteGames = json_decode($favoriteGames, true) ?: [];
        }

        $motivation = $profile?->motivation;
        if (is_string($motivation)) {
            $motivation = json_decode($motivation, true) ?: [];
        }

        return [
            'participant_id' => (string) $this->participant->id,
            'registered_at' => $this->participant->created_at
              ? Carbon::parse($this->participant->created_at)->format('Y-m-d H:i')
              : '',
            'tournament' => $this->resolveTournamentTitle($tournament?->title),
            'location' => (string) ($this->participant->location ?? ''),
            'name' => (string) ($user?->name ?? ''),
            'email' => (string) ($user?->email ?? ''),
            'phone' => (string) ($user?->phone ?? ''),
            'birthdate' => $profile?->birthdate
              ? Carbon::parse($profile->birthdate)->format('Y-m-d')
              : '',
            'whatsapp' => (string) ($profile?->whatsapp ?? ''),
            'gender' => ProfileFormOptions::labelFor('gender', $profile?->gender),
            'city' => ProfileFormOptions::labelFor('city', $profile?->city),
            'skill_level' => ProfileFormOptions::labelFor('skill_level', $profile?->skill_level),
            'has_ps5' => ProfileFormOptions::formatBoolean($profile?->has_ps5),
            'primary_platform' => ProfileFormOptions::labelFor('primary_platform', $profile?->primary_platform),
            'weekly_hours' => ProfileFormOptions::labelFor('weekly_hours', $profile?->weekly_hours),
            'favorite_games' => ProfileFormOptions::formatList($favoriteGames, 'favorite_games'),
            'gt7_ranking' => ProfileFormOptions::labelFor('gt7_ranking', $profile?->gt7_ranking),
            'toyota_gr_knowledge' => ProfileFormOptions::labelFor('toyota_gr_knowledge', $profile?->toyota_gr_knowledge),
            'favorite_car' => (string) ($profile?->favorite_car ?? ''),
            'participated_before' => ProfileFormOptions::formatBoolean($profile?->participated_before),
            'wants_training' => ProfileFormOptions::formatBoolean($profile?->wants_training),
            'join_whatsapp' => ProfileFormOptions::formatBoolean($profile?->join_whatsapp),
            'heard_about' => ProfileFormOptions::labelFor('heard_about', $profile?->heard_about),
            'motivation' => ProfileFormOptions::formatList($motivation, 'motivation'),
            'preferred_time' => ProfileFormOptions::labelFor('preferred_time', $profile?->preferred_time),
            'suggestions' => (string) ($profile?->suggestions ?? ''),
            'regular_games' => (string) ($profile?->regular_games ?? ''),
            'profile_completion_percent' => (string) ProfileCompletion::for($profile)->percentage(),
        ];
    }

    /** @return array<string, string> */
    public static function columnLabels(): array
    {
        return self::COLUMN_LABELS;
    }

    private function resolveTournamentTitle(mixed $title): string
    {
        if (is_array($title)) {
            $locale = app()->getLocale();

            return (string) ($title[$locale] ?? reset($title) ?: '');
        }

        return (string) ($title ?? '');
    }
}

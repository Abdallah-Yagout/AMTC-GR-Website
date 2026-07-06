<?php

namespace App\Support;

use App\Models\Participant;
use App\Models\Tournament;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class ParticipantExportSupport
{
    public static function tournamentSelect(): Select
    {
        return Select::make('tournament_id')
            ->label(__('Tournament'))
            ->placeholder(__('Select a tournament to export'))
            ->options(self::tournamentOptions())
            ->searchable()
            ->required()
            ->native(false);
    }

    /** @return array<int|string, string> */
    public static function tournamentOptions(): array
    {
        return Tournament::query()
            ->orderByDesc('start_date')
            ->get()
            ->mapWithKeys(function (Tournament $tournament): array {
                $title = self::resolveTournamentTitle($tournament->title);
                $date = $tournament->start_date
                    ? Carbon::parse($tournament->start_date)->format('Y-m-d')
                    : 'N/A';

                return [$tournament->id => "{$title} · {$date}"];
            })
            ->all();
    }

    public static function queryForTournament(int $tournamentId): Builder
    {
        return Participant::query()
            ->where('tournament_id', $tournamentId)
            ->with(['user', 'profile', 'tournament']);
    }

    public static function exportFileNameSlug(?int $tournamentId): string
    {
        if ($tournamentId === null) {
            return 'all';
        }

        $tournament = Tournament::query()->find($tournamentId);

        if ($tournament === null) {
            return 'tournament-'.$tournamentId;
        }

        $slug = Str::slug(self::resolveTournamentTitle($tournament->title));

        return filled($slug) ? $slug : 'tournament-'.$tournamentId;
    }

    private static function resolveTournamentTitle(mixed $title): string
    {
        if (is_array($title)) {
            $locale = app()->getLocale();

            return (string) ($title[$locale] ?? reset($title) ?: '');
        }

        return (string) ($title ?? '');
    }
}

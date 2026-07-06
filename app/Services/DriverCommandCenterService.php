<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\User;
use App\Support\RaceRankBadge;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DriverCommandCenterService
{
    private const LIST_LIMIT = 5;

    /**
     * @return array{
     *     stats: array{registered: int, results: int, best_finish: ?int, upcoming: int},
     *     latest_result: ?array{tournament_name: string, position: ?int, time_taken: ?string},
     *     tournaments: list<array{title: string, meta: ?string, status: string, status_label: string}>,
     *     results: list<array{title: string, meta: ?string, position: ?int, position_label: string, rank_class: string}>
     * }
     */
    public function build(User $user): array
    {
        $registrations = Participant::query()
            ->forUser($user->id)
            ->withTournamentAndResults()
            ->latest('id')
            ->get();

        $resultsHistory = $registrations
            ->flatMap(fn (Participant $participant) => $this->mapParticipantResults($participant))
            ->sortByDesc('resulted_at')
            ->values();

        $bestFinish = $resultsHistory
            ->pluck('position')
            ->filter(fn ($position) => ! is_null($position))
            ->min();

        $latestResult = $resultsHistory->first();

        return [
            'stats' => [
                'registered' => $registrations->count(),
                'results' => $resultsHistory->count(),
                'best_finish' => $bestFinish,
                'upcoming' => $this->countUpcoming($registrations),
            ],
            'latest_result' => $latestResult ? [
                'tournament_name' => $latestResult['tournament_name'],
                'position' => $latestResult['position'],
                'time_taken' => $latestResult['time_taken'],
            ] : null,
            'tournaments' => $registrations
                ->take(self::LIST_LIMIT)
                ->map(fn (Participant $registration) => $this->mapTournamentRow($registration))
                ->values()
                ->all(),
            'results' => $resultsHistory
                ->take(self::LIST_LIMIT)
                ->map(fn (array $result) => $this->mapResultRow($result))
                ->values()
                ->all(),
        ];
    }

    private function countUpcoming(Collection $registrations): int
    {
        return $registrations->filter(function (Participant $participant): bool {
            $startDate = $participant->tournament?->start_date;

            return filled($startDate) && Carbon::parse($startDate)->isFuture();
        })->count();
    }

    /**
     * @return array{title: string, meta: ?string, status: string, status_label: string}
     */
    private function mapTournamentRow(Participant $registration): array
    {
        $tournament = $registration->tournament;
        $hasResult = $registration->hasPublishedResult();

        return [
            'title' => $this->resolveTournamentTitle($tournament?->title),
            'meta' => $this->formatTournamentMeta($tournament?->start_date, $registration->location),
            'status' => $hasResult ? 'result' : 'upcoming',
            'status_label' => $hasResult ? __('Result Published') : __('Upcoming'),
        ];
    }

    /**
     * @param  array{tournament_name: string, location: ?string, position: ?int, time_taken: ?string}  $result
     * @return array{title: string, meta: ?string, position: ?int, position_label: string, rank_class: string}
     */
    private function mapResultRow(array $result): array
    {
        $position = $result['position'] ?? null;

        return [
            'title' => $result['tournament_name'] ?? __('Tournament'),
            'meta' => $this->formatResultMeta($result['location'] ?? null, $result['time_taken'] ?? null),
            'position' => $position,
            'position_label' => is_null($position) ? '--' : '#'.$position,
            'rank_class' => RaceRankBadge::cssClass($position),
        ];
    }

    private function formatTournamentMeta(mixed $startDate, ?string $location): ?string
    {
        $parts = [];

        if (filled($startDate)) {
            $parts[] = Carbon::parse($startDate)->format('d M Y');
        }

        if (filled($location)) {
            $parts[] = $location;
        }

        return $parts === [] ? null : implode(' · ', $parts);
    }

    private function formatResultMeta(?string $location, ?string $timeTaken): ?string
    {
        $parts = [];

        if (filled($location)) {
            $parts[] = $location;
        }

        if (filled($timeTaken)) {
            $parts[] = __('Time').': '.$timeTaken;
        }

        return $parts === [] ? null : implode(' · ', $parts);
    }

    private function mapParticipantResults(Participant $participant): Collection
    {
        return $participant->leaderboards
            ->map(function ($leaderboard) use ($participant) {
                $position = $leaderboard->pivot?->position;
                $timeTaken = $leaderboard->pivot?->time_taken;
                $status = $leaderboard->pivot?->status;

                if (is_null($position) && is_null($timeTaken) && is_null($status)) {
                    return null;
                }

                $tournament = $leaderboard->tournament ?? $participant->tournament;

                return [
                    'tournament_name' => $this->resolveTournamentTitle($tournament?->title),
                    'tournament_slug' => $tournament?->slug,
                    'location' => $leaderboard->location ?? $participant->location,
                    'position' => $position,
                    'time_taken' => $timeTaken,
                    'status' => $status,
                    'resulted_at' => $leaderboard->updated_at ?? $leaderboard->created_at,
                ];
            })
            ->filter();
    }

    private function resolveTournamentTitle(mixed $title): string
    {
        if (is_array($title)) {
            $locale = app()->getLocale();

            return (string) ($title[$locale] ?? reset($title) ?? __('Tournament'));
        }

        return filled($title) ? (string) $title : __('Tournament');
    }
}

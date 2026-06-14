<?php

namespace App\Http\Controllers;

use App\Models\GameUserStat;
use App\Models\Participant;
use App\Services\EngagementHubService;
use App\Support\GamePointsTierProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProfileController extends Controller
{
    public function show(Request $request, EngagementHubService $engagementHubService)
    {
        $user = $request->user();

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

        $driverStats = [
            'total_registered' => $registrations->count(),
            'results_published' => $resultsHistory->count(),
            'best_finish' => $bestFinish,
            'upcoming_count' => $registrations->filter(function (Participant $participant) {
                $startDate = $participant->tournament?->start_date;
                if (empty($startDate)) {
                    return false;
                }

                return Carbon::parse($startDate)->isFuture();
            })->count(),
        ];

        $gameStat = GameUserStat::query()->where('user_id', $user->id)->first();
        $gamePoints = (int) ($gameStat->points ?? 0);
        $gameRank = null;
        if ($gameStat && $gamePoints > 0) {
            $gameRank = GameUserStat::query()
                ->where('points', '>', $gamePoints)
                ->count() + 1;
        }

        return view('profile.show', [
            'driverStats' => $driverStats,
            'registrations' => $registrations,
            'resultsHistory' => $resultsHistory,
            'latestResult' => $resultsHistory->first(),
            'gamePoints' => $gamePoints,
            'gameRank' => $gameRank,
            'gameTierTrack' => GamePointsTierProgress::build($gamePoints),
            'engagementHub' => $engagementHubService->hubPayload($user),
        ]);
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

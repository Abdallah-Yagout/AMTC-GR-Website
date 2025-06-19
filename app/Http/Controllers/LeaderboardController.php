<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\participant;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeaderboardController extends Controller
{

    public function index(Request $request)
    {
        // Get selected year from request or default to current year
        $selectedYear = $request->input('year', date('Y'));

        // Get all tournaments grouped by year with their leaderboards and participants
        $tournamentsByYear = Tournament::with([
            'leaderboards.participants.user',
            'leaderboards.participants' => function($query) {
                $query->orderByPivot('position');
            }
        ])
            ->orderBy('start_date')
            ->get()
            ->groupBy(function($tournament) {
                return \Carbon\Carbon::parse($tournament->start_date)->format('Y');
            });

        // Get available years (sorted newest first)
        $availableYears = $tournamentsByYear->keys()->sortDesc()->values()->toArray();

        // Set default year if not selected
        if (!$selectedYear && count($availableYears) > 0) {
            $selectedYear = $availableYears[0];
        }

        // Organize data structure
        $leaderboardData = [
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'yearData' => []
        ];

        // Process each year's tournaments
        foreach ($tournamentsByYear as $year => $tournaments) {
            $yearEntry = [
                'year' => $year,
                'mainTournaments' => collect(),
                'finalTournaments' => collect()
            ];

            // Find all main tournaments (where tournament_id is null)
            $mainTournaments = $tournaments->whereNull('tournament_id');

            foreach ($mainTournaments as $mainTournament) {
                $finalTournament = $tournaments->firstWhere('tournament_id', $mainTournament->id);

                // Group leaderboards by location with participants
                $locationLeaderboards = $mainTournament->leaderboards
                    ->map(function ($leaderboard) {
                        return [
                            'location' => $leaderboard->location,
                            'participants' => $leaderboard->participants->map(function ($participant) {
                                return [
                                    'user' => $participant->user,
                                    'position' => $participant->pivot->position,
                                    'time_taken' => $participant->pivot->time_taken,
                                    'status' => $participant->pivot->status
                                ];
                            })
                        ];
                    })
                    ->groupBy('location')
                    ->map(fn($group) => $group->flatMap->participants->sortBy('position')->values());

                $yearEntry['mainTournaments']->push([
                    'main' => $mainTournament,
                    'final' => $finalTournament,
                    'locations' => $locationLeaderboards
                ]);
            }

            // For the selected year, prepare season leaderboard
            if ($year == $selectedYear) {
                // Get all participants for the year with their pivot data
                $yearParticipants = Participant::whereHas('leaderboards', function($query) use ($year) {
                    $query->whereHas('tournament', function($q) use ($year) {
                        $q->whereYear('start_date', $year);
                    });
                })
                    ->with(['user', 'leaderboards' => function($query) {
                        $query->withPivot('position', 'time_taken');
                    }])
                    ->get();

                // Group by location
                $locationLeaderboards = collect();
                foreach ($yearParticipants as $participant) {
                    foreach ($participant->leaderboards as $leaderboard) {
                        $locationLeaderboards->push([
                            'location' => $leaderboard->location,
                            'participant' => [
                                'user' => $participant->user,
                                'position' => $leaderboard->pivot->position,
                                'time_taken' => $leaderboard->pivot->time_taken,

                            ]
                        ]);
                    }
                }

                $groupedByLocation = $locationLeaderboards
                    ->groupBy('location')
                    ->map(fn($group) => $group->pluck('participant')->sortBy('position')->values());

                // Season aggregate leaderboard
                $seasonAggregate = $yearParticipants
                    ->groupBy('user_id')
                    ->map(function($participants) {
                        $user = $participants->first()->user;


                        return [
                            'user' => $user,
                            'participations' => $participants->count()
                        ];
                    })
                    ->values();

                $yearEntry['seasonLeaderboards'] = $groupedByLocation;
                $yearEntry['seasonAggregate'] = $seasonAggregate;
            }

            $leaderboardData['yearData'][$year] = $yearEntry;
        }

        return view('leaderboard.index', $leaderboardData);
    }


}

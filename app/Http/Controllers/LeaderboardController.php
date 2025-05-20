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

        // Get all tournaments grouped by year
        $tournamentsByYear = Tournament::orderBy('start_date')
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

        // Get tournaments for selected year
        $yearTournaments = $tournamentsByYear->get($selectedYear, collect());

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

                $mainTournament->load(['leaderboards' => function($query) {
                    $query->with('user')->orderBy('position');
                }]);

                if ($finalTournament) {
                    $finalTournament->load(['leaderboards' => function($query) {
                        $query->with('user')->orderBy('position');
                    }]);
                }

                // Group leaderboards by location for this main tournament
                $locationLeaderboards = $tournaments
                    ->filter(fn($t) => $t->tournament_id === null && $t->id === $mainTournament->id)
                    ->flatMap(function($tournament) {
                        return $tournament->leaderboards->map(function ($leaderboard) use ($tournament) {
                            $leaderboard->tournament_location = $tournament->location;
                            return $leaderboard;
                        });
                    })->groupBy('tournament_location')
                    ->map(fn($group) => $group->sortBy('position')->values());

                $yearEntry['mainTournaments']->push([
                    'main' => $mainTournament,
                    'final' => $finalTournament,
                    'locations' => $locationLeaderboards
                ]);
            }


            // For the selected year, also prepare season leaderboard
            if ($year == $selectedYear) {
                // Group participants by location
                $locationLeaderboards = $tournaments->flatMap(function($tournament) {
                    return $tournament->leaderboards->map(function ($leaderboard) use ($tournament) {
                        $leaderboard->tournament_location = $tournament->location;
                        return $leaderboard;
                    });
                })->groupBy('tournament_location')
                    ->map(function($locationGroup) {
                        return $locationGroup->sortBy('position')->values();
                    });

                // Season leaderboard - aggregate by user across all tournaments
                $seasonLeaderboard = $tournaments->flatMap(function($tournament) {
                    return $tournament->leaderboards;
                })->groupBy('user_id')->map(function($entries) {
                    $user = $entries->first()->user;
                    $totalPoints = $entries->sum('points'); // or whatever score metric
                    return (object)[
                        'user' => $user,
                        'total_points' => $totalPoints,
                    ];
                })->sortByDesc('total_points')->values();

                $yearEntry['seasonLeaderboards'] = $locationLeaderboards;
                $yearEntry['seasonAggregate'] = $seasonLeaderboard;
            }


            $leaderboardData['yearData'][$year] = $yearEntry;
        }

        return view('leaderboard.index', $leaderboardData);
    }
    public function apply(Tournament $id)
    {

        return view('tournament.apply', ['tournament' => $id]);

    }

    public function submit(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:leaderboard,email',
                'city' => 'required|string|max:255',
                'tournamentId' => 'required|exists:tournaments,id', // Make sure tournament exists
            ]);

            // Create participant with all required fields
            participant::create([
                'user_id' => auth()->id(),
                'tournament_id' => $validated['tournamentId'],
                'location' => $validated['city'],
                // Add any other fields you need to save
            ]);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }


        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
}

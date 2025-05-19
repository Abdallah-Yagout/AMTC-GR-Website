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

        // Get all tournaments with their leaderboards
        $tournaments = Tournament::with(['leaderboards' => function($query) {
            $query->with('user')->orderBy('position');
        }])->orderBy('start_date')->get();
        // Group tournaments by year
        $tournamentsByYear = $tournaments->groupBy(function($tournament) {
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
        $data = [
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'tournaments' => [],
            'seasonLeaderboard' => collect(),
        ];


        // Process tournaments for selected year
        foreach ($yearTournaments as $tournament) {
            $tournamentData = [
                'id' => $tournament->id,
                'title' => $tournament->title,
                'is_final' => !is_null($tournament->tournament_id),
                'locationLeaderboards' => $tournament->leaderboards->groupBy('location'),
                'tournament_id' => $tournament->tournament_id,
            ];

            $data['tournaments'][] = $tournamentData;
        }

        // Prepare season leaderboard (combines all tournaments)
        $data['seasonLeaderboard'] = $yearTournaments->flatMap(function($tournament) {
            return $tournament->leaderboards;
        })->sortBy('position')->values();

        return view('leaderboard.index', $data);
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

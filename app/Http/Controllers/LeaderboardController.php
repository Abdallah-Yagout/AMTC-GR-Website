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


        // Get active tournament (where tournament_id is null - main tournament)
        $data['tournament'] = Tournament::active()
            ->whereNull('tournament_id')
            ->first();
        // Initialize variables to avoid undefined errors
        $data['finalTournament'] = null;
        $data['leaderboard'] = collect();
        $data['finalLeaderboard'] = collect();
        $data['season_leaderboard'] = collect();

        // Get leaderboard for active main tournament if exists
        if ($data['tournament']) {
            $data['leaderboard'] = Leaderboard::with('user')
                ->where('tournament_id', $data['tournament']->id)
                ->orderBy('position')
                ->get();

            // Get final tournament (where tournament_id points to the main tournament)
            $data['finalTournament'] = Tournament::active()->where('tournament_id', $data['tournament']->id)
                ->first();

            // Get final leaderboard if exists
            if ($data['finalTournament']) {
                $data['finalLeaderboard'] = Leaderboard::active()->with('user')
                    ->where('tournament_id', $data['finalTournament']->id)
                    ->orderBy('position')
                    ->get();
            }

            // Get season leaderboard grouped by location
            // Include both main tournament and its sub-tournaments
            $data['season_leaderboard'] = Leaderboard::with('user')
                ->whereHas('tournament', function($query) use ($data) {
                    $query->where(function($q) use ($data) {
                            $q->whereNull('tournament_id') // Main tournament
                            ->orWhere('tournament_id', $data['tournament']->id); // Its sub-tournaments
                        });
                })
                ->get()
                ->groupBy('location')
                ->map(function($locationGroup) {
                    return $locationGroup->sortBy('position')->values();
                });

        }

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

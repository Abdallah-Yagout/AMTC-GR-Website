<?php

namespace App\Http\Controllers;

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
        // Get all tournaments
        $data['tournaments'] = Tournament::orderBy('date', 'desc')->get();

        // Get active tournament
        $data['tournament'] = Tournament::active()->first();

        // Get final tournament (assuming it's marked as is_final = true)
        $data['finalTournament'] = Tournament::where('is_final', true)->first();

        // Real-time leaderboard for current tournament
        $data['leaderboard'] = participant::where('tournament_id', $data['tournament']->id ?? null)
            ->whereNotNull('position')
            ->with('user')
            ->get();

        // Final tournament leaderboard if exists
        if ($data['finalTournament']) {
            $data['finalLeaderboard'] = participant::where('tournament_id', $data['finalTournament']->id)
                ->whereNotNull('position')
                ->with('user')
                ->orderBy('position')
                ->get();
        }

        // Leaderboard per location (all tournaments combined)
        $participantsByLocation = participant::select([
            'location',
            'user_id',
            'position',
            'users.name',
            'users.profile_photo_path',
            DB::raw('COUNT(CASE WHEN is_winner = 1 THEN 1 END) as wins'),
            DB::raw('SUM(points) as total_points'),
            DB::raw('AVG(time_taken) as avg_time')
        ])
            ->join('users', 'users.id', '=', 'participants.user_id')
            ->groupBy('location', 'user_id', 'users.name', 'users.profile_photo_path')
            ->orderBy('location')
            ->orderByDesc('wins')
            ->orderByDesc('total_points')
            ->orderBy('avg_time')
            ->get()
            ->groupBy('location');

        $data['season_leaderboard'] = $participantsByLocation;

        return view('leaderboard.index', $data);
    }    public function apply(Tournament $id)
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

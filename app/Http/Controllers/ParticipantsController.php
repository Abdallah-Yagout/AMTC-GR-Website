<?php

namespace App\Http\Controllers;

use App\Models\participants;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParticipantsController extends Controller
{
    public function index(Request $request)
    {
        // Get all seasons for the dropdown
        $seasons = Tournament::select('season')->distinct()->orderBy('season', 'desc')->get();

        // Get the selected season from request or use the latest one
        $selectedSeason = $request->season ?? Tournament::max('season');

        // Get active tournament for real-time results
        $data['tournament'] = Tournament::active()->first();

        // Get participants for real-time results
        $data['participants'] = Participants::where('tournament_id', $data['tournament']->id)
            ->whereNotNull('position')
            ->get();

        // Get season leaderboard data with wins and points
        $data['season_leaderboard'] = Participants::select([
            'user_id',
            'position',
            'users.name',
            'users.profile_photo_path',
            DB::raw('COUNT(CASE WHEN is_winner = 1 THEN 1 END) as wins'),
            DB::raw('SUM(points) as total_points'),
            DB::raw('AVG(time_taken) as avg_time')
        ])
            ->join('users', 'users.id', '=', 'participants.user_id')
            ->whereHas('tournament', function($query) use ($selectedSeason) {
                $query->where('season', $selectedSeason);
            })
            ->groupBy('user_id', 'users.name', 'users.profile_photo_path')
            ->orderByDesc('wins')
            ->orderByDesc('total_points')
            ->orderBy('avg_time')
            ->get();

        $data['seasons'] = $seasons;
        $data['selectedSeason'] = $selectedSeason;

        return view('participants.index', $data);
    }

    public function apply($id)
    {

        return view('tournament.apply', ['tournamentId' => $id]);

    }

    public function submit(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:participants,email',
                'city' => 'required|string|max:255',
                'tournamentId' => 'required|exists:tournaments,id', // Make sure tournament exists
            ]);

            // Create participant with all required fields
            participants::create([
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

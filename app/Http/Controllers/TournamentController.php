<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class TournamentController extends Controller
{

    public function index(Request $request)
    {
        // Get current year from query string or default to first year
        $selectedYear = $request->query('year');

        // Fetch and sort all tournaments
        $tournaments = Tournament::orderBy('start_date')
            ->get();
        // Group tournaments by year
        $tournamentsByYear = $tournaments->groupBy(function ($tournament) {
            return \Carbon\Carbon::parse($tournament->start_date)->format('Y');
        });

        // Set default selected year if not set
        if (!$selectedYear && $tournamentsByYear->keys()->isNotEmpty()) {
            $selectedYear = $tournamentsByYear->keys()->first();
        }


        // Paginate each year's tournaments
        $paginatedTournamentsByYear = new Collection();
        foreach ($tournamentsByYear as $year => $yearTournaments) {
            $page = ($year == $selectedYear) ? $request->query('page', 1) : 1;
            $perPage = 5;

            $paginated = new LengthAwarePaginator(
                $yearTournaments->forPage($page, $perPage),
                $yearTournaments->count(),
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => array_merge($request->query(), ['year' => $year])
                ]
            );

            $paginatedTournamentsByYear->put($year, $paginated);
        }

        return view('tournament.index', compact(
            'paginatedTournamentsByYear',
            'selectedYear'
        ));
    }
    public function apply(Tournament $id)
    {

        return view('tournament.apply', ['tournament' => $id]);

    }
    public function submit(Request $request)
    {
        $profile = auth()->user()->profile;

        // List of required profile fields
        $requiredProfileFields = [
            'birthdate',
            'whatsapp',
            'gender',
            'city',
            'skill_level',
            'primary_platform',
            'regular_games',
            'weekly_hours',
            'favorite_games',
            'gt7_ranking',
            'toyota_gr_knowledge',
            'favorite_car',
            'participated_before',
            'heard_about',
            'motivation',
            'preferred_time',
        ];

        // Check for missing profile fields
        $missingFields = [];

        foreach ($requiredProfileFields as $field) {
            if (is_null($profile->$field) || $profile->$field === '' || $profile->$field === '[]') {
                $missingFields[] = $field;
            }
        }

        // If any required profile field is missing, redirect back with error
        if (!empty($missingFields)) {
            return redirect()->back()->withErrors([
                'profile' => 'Please complete your profile before submitting the application.',
            ])->withInput();
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:leaderboard,email',
                'city' => 'required|string|max:255',
                'tournamentId' => 'required|exists:tournaments,id',
            ]);

            participant::create([
                'user_id' => auth()->id(),
                'tournament_id' => $validated['tournamentId'],
                'location' => $validated['city'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }


}

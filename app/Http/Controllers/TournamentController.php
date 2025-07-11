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
        $user = auth()->user();
        $hasSubmitted = false;

        if ($user) {
            $hasSubmitted = Participant::where('user_id', $user->id)
                ->where('tournament_id', $id->id)
                ->exists();
        }

        return view('tournament.apply', [
            'tournament' => $id,
            'hasSubmitted' => $hasSubmitted
        ]);
    }
    public function submit(Request $request)
    {
        $auth = auth()->user();
        if (!$auth) {
            return redirect()->route('login')->withErrors([
                'auth' => __('You must be logged in to submit an application.')
            ]);
        }

        // Get user profile
        $profile = $auth->profile;
        if (!$profile) {
            return redirect()->back()->withErrors([
                'profile' => __('User profile not found.')
            ]);
        }

        // Check if user already submitted for this tournament
        $existingSubmission = Participant::where('user_id', $auth->id)
            ->where('tournament_id', $request->tournamentId)
            ->first();

        if ($existingSubmission) {
            return redirect()->back()->withErrors([
                'submission' => __('You have already submitted an application for this tournament.')
            ]);
        }

        // List of required profile fields
        $requiredProfileFields = [
            'birthdate',
            'whatsapp',
            'gender',
            'city',
            'skill_level',
            'primary_platform',
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
                'profile' => __('Please complete your profile before submitting the application.',)
            ])->withInput();
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['male', 'female'])],
                'phone' => 'required|string|max:20',
                'email' => 'required|email',
                'city' => 'required|string|max:255',
                'tournamentId' => 'required|exists:tournaments,id',
            ]);

            Participant::create([
                'user_id' => $auth->id,
                'tournament_id' => $validated['tournamentId'],
                'leaderboard_id' => null,
                'location' => $validated['city'],
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', __('Application submitted successfully!'));
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
}

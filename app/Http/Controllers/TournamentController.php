<?php

namespace App\Http\Controllers;

use App\Models\participant;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::all();

        // Group tournaments by location with participant count for each
        $tournamentsByLocation = [];

        foreach ($tournaments as $tournament) {
            foreach ( $tournament->location as $location) {
                $participantCount = participant::where('tournament_id', $tournament->id)
                    ->where('location', $location)
                    ->count();

                $t = clone $tournament;
                $t->location_participant_count = $participantCount;
                $t->current_location = $location;

                $tournamentsByLocation[$location][] = $t;
            }
        }

        return view('tournament.index', compact('tournamentsByLocation'));
    }

}

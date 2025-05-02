<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $data['tournamentsByLocation'] = Tournament::all()->groupBy('location');

        return view('tournament.index',$data);
    }
}

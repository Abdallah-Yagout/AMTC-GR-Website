<?php

namespace App\Http\Controllers;

class GamesLeaderboardController extends Controller
{
    public function index()
    {
        return redirect()->route('games.index', ['tab' => 'board']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\GameUserStat;
use App\Services\DriverCommandCenterService;
use App\Services\EngagementHubService;
use App\Support\GamePointsTierProgress;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(
        Request $request,
        DriverCommandCenterService $commandCenterService,
        EngagementHubService $engagementHubService,
    ) {
        $user = $request->user();

        $gameStat = GameUserStat::query()->where('user_id', $user->id)->first();
        $gamePoints = (int) ($gameStat->points ?? 0);
        $gameRank = null;

        if ($gameStat && $gamePoints > 0) {
            $gameRank = GameUserStat::query()
                ->where('points', '>', $gamePoints)
                ->count() + 1;
        }

        return view('profile.show', [
            'commandCenter' => $commandCenterService->build($user),
            'gamePoints' => $gamePoints,
            'gameRank' => $gameRank,
            'gameTierTrack' => GamePointsTierProgress::build($gamePoints),
            'engagementHub' => $engagementHubService->hubPayload($user),
        ]);
    }
}

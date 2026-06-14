<?php

namespace App\Http\Controllers;

use App\Models\GameDefinition;
use App\Models\GameUserStat;
use App\Services\GamePointsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function index(Request $request, GamePointsService $gamePointsService)
    {
        $tab = $request->query('tab', 'hub');
        if (! in_array($tab, ['hub', 'board'], true)) {
            $tab = 'hub';
        }

        $user = $request->user();
        $stat = null;
        $claimedToday = false;
        $viewerRank = null;

        if ($user) {
            $stat = $gamePointsService->getOrCreateStat($user);
            $claimedToday = $stat->last_daily_claim_date
                && $stat->last_daily_claim_date->toDateString() === Carbon::today()->toDateString();

            $userStat = GameUserStat::query()->where('user_id', $user->id)->first();
            if ($userStat && (int) $userStat->points > 0) {
                $viewerRank = GameUserStat::query()
                    ->where('points', '>', $userStat->points)
                    ->count() + 1;
            }
        }

        $leaders = GameUserStat::query()
            ->with('user')
            ->orderByDesc('points')
            ->orderBy('updated_at')
            ->limit(100)
            ->get();

        $arcadeGames = GameDefinition::query()->enabled()->ordered()->get();

        return view('games.index', [
            'stat' => $stat,
            'claimedToday' => $claimedToday,
            'leaders' => $leaders,
            'activeTab' => $tab,
            'viewerRank' => $viewerRank,
            'gameRewards' => $gamePointsService->rewardAmounts(),
            'arcadeGames' => $arcadeGames,
        ]);
    }

    public function claimDaily(Request $request, GamePointsService $gamePointsService)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $result = $gamePointsService->claimDaily($user);
        if ($result['awarded']) {
            return redirect()
                ->route('games.index')
                ->with('games_success', __('Daily reward claimed! +:points points', ['points' => $gamePointsService->dailyClaimAmount()]));
        }

        return redirect()
            ->route('games.index')
            ->with('games_error', __('You already claimed your daily reward today.'));
    }
}

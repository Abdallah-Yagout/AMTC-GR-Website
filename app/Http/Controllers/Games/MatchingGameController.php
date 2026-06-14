<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameDefinition;
use App\Services\EngagementHubService;
use App\Services\GameAwardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchingGameController extends Controller
{
    public function show()
    {
        $definition = GameDefinition::query()
            ->where('slug', GameAwardService::MATCHING_SLUG)
            ->where('is_enabled', true)
            ->first();

        if (! $definition) {
            abort(404);
        }

        return view('games.matching.play', [
            'definition' => $definition,
        ]);
    }

    public function start(Request $request, GameAwardService $gameAwardService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false, 'error' => 'unauthenticated'], 401);
        }

        $result = $gameAwardService->startMatchingSession($user);
        $status = $result['ok'] ? 200 : 422;

        return response()->json($result, $status);
    }

    public function complete(Request $request, GameAwardService $gameAwardService): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false, 'error' => 'unauthenticated'], 401);
        }

        $validated = $request->validate([
            'session_id' => ['required', 'integer', 'min:1'],
            'flip_sequence' => ['required', 'array', 'min:4'],
            'flip_sequence.*' => ['integer', 'min:0'],
            'duration_ms' => ['required', 'integer', 'min:0', 'max:600000'],
        ]);

        $result = $gameAwardService->completeMatchingSession(
            $user,
            (int) $validated['session_id'],
            array_map('intval', $validated['flip_sequence']),
            (int) $validated['duration_ms']
        );

        if (! $result['ok']) {
            return response()->json($result, 422);
        }

        if (($result['points_awarded'] ?? 0) > 0) {
            app(EngagementHubService::class)->tryAwardOneTimeMission(
                $user,
                EngagementHubService::MISSION_PLAY_GAME
            );
        }

        return response()->json($result);
    }
}

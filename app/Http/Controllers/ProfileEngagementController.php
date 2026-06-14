<?php

namespace App\Http\Controllers;

use App\Services\EngagementHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileEngagementController extends Controller
{
    public function claimCheckIn(Request $request, EngagementHubService $engagementHubService): RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $result = $engagementHubService->claimCheckIn($user);

        if (! $result['ok']) {
            $message = ($result['reason'] ?? '') === 'already_claimed'
                ? __('You already checked in today.')
                : __('Check-in could not be completed.');

            return redirect()->route('profile.show')->with('engagement_error', $message);
        }

        return redirect()->route('profile.show')->with(
            'engagement_success',
            __('Check-in complete! +:pts points', ['pts' => number_format((int) ($result['points_awarded'] ?? 0))])
        );
    }
}

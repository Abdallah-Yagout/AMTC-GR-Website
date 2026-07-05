<div
    @class([
        'profile-completion-banner profile-completion-banner--embedded',
        'profile-completion-banner--complete' => $isComplete,
    ])
    aria-labelledby="profile-completion-title"
>
    <div class="profile-completion-banner-top">
        <p id="profile-completion-title" class="profile-completion-kicker">
            {{ __('Profile completion') }}
        </p>
        <p class="profile-completion-percent" aria-hidden="true">
            {{ $percentage }}%
        </p>
    </div>

    <div
        class="profile-completion-bar"
        role="progressbar"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow="{{ $percentage }}"
        aria-labelledby="profile-completion-title"
    >
        <span class="profile-completion-bar-fill" style="width: {{ $percentage }}%;"></span>
    </div>

    <div class="profile-completion-banner-foot">
        @if ($isComplete)
            <p class="profile-completion-message profile-completion-message--complete">
                {{ __('Your profile is complete. You are ready to register for tournaments.') }}
            </p>
        @else
            <p class="profile-completion-message">
                {{ trans_choice(':count field remaining|:count fields remaining', $remainingCount, ['count' => $remainingCount]) }}
            </p>
        @endif

        <p class="profile-completion-meta">
            {{ __(':completed of :total required fields completed', [
                'completed' => $completedCount,
                'total' => $totalCount,
            ]) }}
        </p>
    </div>
</div>

@php
    $hub = $engagementHub ?? [];
    $checkIn = $hub['check_in'] ?? [];
    $missions = $hub['missions'] ?? [];
    $dayPoints = $checkIn['day_points'] ?? [];
    $claimedToday = (bool) ($checkIn['claimed_today'] ?? false);
    $passedSlots = (int) ($checkIn['passed_slots'] ?? 0);
    $highlightStep = (int) ($checkIn['highlight_step'] ?? 1);
    $streakBroken = (bool) ($checkIn['streak_broken'] ?? false);
@endphp

<section class="profile-engagement" aria-labelledby="profile-engagement-title">
    @if (session('engagement_success'))
        <p class="profile-engagement-flash profile-engagement-flash--success" role="status">{{ session('engagement_success') }}</p>
    @endif
    @if (session('engagement_error'))
        <p class="profile-engagement-flash profile-engagement-flash--error" role="alert">{{ session('engagement_error') }}</p>
    @endif

    <div class="profile-engagement-grid">
        <div class="profile-engagement-panel">
            <h2 id="profile-engagement-title" class="profile-engagement-heading">{{ __('Daily check in') }}</h2>
            @if ($streakBroken && ! $claimedToday)
                <p class="profile-engagement-hint">{{ __('Missed a day — your streak resets to day 1 on next check-in.') }}</p>
            @endif
            <div class="profile-engagement-days" role="list">
                @for ($d = 1; $d <= 7; $d++)
                    @php
                        $pts = (int) ($dayPoints[$d] ?? 0);
                        $isMystery = $d === 7;
                        $isDone = $d <= $passedSlots && ! $streakBroken;
                        $isNext = $d === $highlightStep && ! $claimedToday;
                    @endphp
                    <div
                        @class([
                            'profile-engagement-day',
                            'profile-engagement-day--done' => $isDone,
                            'profile-engagement-day--next' => $isNext,
                            'profile-engagement-day--mystery' => $isMystery,
                        ])
                        role="listitem"
                    >
                        @if ($isMystery)
                            <span class="profile-engagement-day-badge">{{ __('Mystery') }}</span>
                        @endif
                        @if ($isDone)
                            <span class="profile-engagement-day-check" aria-hidden="true">&#10003;</span>
                        @elseif ($isMystery)
                            <span class="profile-engagement-day-mystery-icon" aria-hidden="true">?</span>
                        @else
                            <span class="profile-engagement-day-gem" aria-hidden="true"></span>
                        @endif
                        <span class="profile-engagement-day-pts">
                            @if ($isMystery && ! $isDone)
                                {{ __('Bonus') }}
                            @else
                                +{{ number_format($pts) }} {{ __('pts') }}
                            @endif
                        </span>
                        <span class="profile-engagement-day-label">{{ __('Day') }} {{ $d }}</span>
                    </div>
                @endfor
            </div>
            <form method="post" action="{{ route('profile.engagement.check-in') }}" class="profile-engagement-checkin-form">
                @csrf
                <button
                    type="submit"
                    class="profile-engagement-checkin-btn"
                    @disabled($claimedToday)
                >
                    @if ($claimedToday)
                        {{ __('Checked in today') }}
                    @else
                        {{ __('Check in') }}
                    @endif
                </button>
            </form>
        </div>

        <div class="profile-engagement-panel profile-engagement-panel--missions">
            <h2 class="profile-engagement-heading">{{ __('Mission') }}</h2>
            <div class="profile-engagement-missions-scroll">
                <div class="profile-engagement-missions">
                    @foreach ($missions as $mission)
                        <article @class(['profile-engagement-mission', 'profile-engagement-mission--done' => $mission['done_today']])>
                            <div class="profile-engagement-mission-top">
                                <span class="profile-engagement-mission-pts" aria-label="{{ __('Points') }}">
                                    +{{ number_format((int) ($mission['points'] ?? 0)) }}
                                </span>
                                <span class="profile-engagement-mission-hint">{{ $mission['hint'] }}</span>
                            </div>
                            <h3 class="profile-engagement-mission-title">{{ $mission['label'] }}</h3>
                            <a href="{{ $mission['href'] }}" class="profile-engagement-mission-btn">
                                @if ($mission['done_today'])
                                    {{ __('Done') }}
                                @else
                                    {{ __('Go') }}
                                @endif
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

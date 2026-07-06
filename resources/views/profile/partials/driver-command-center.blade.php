@php
    $stats = $commandCenter['stats'] ?? [];
    $latestResult = $commandCenter['latest_result'] ?? null;
    $tournaments = $commandCenter['tournaments'] ?? [];
    $results = $commandCenter['results'] ?? [];
@endphp

<section class="profile-command" aria-labelledby="profile-command-title">
    <header class="profile-command__head">
        <div>
            <h2 id="profile-command-title" class="profile-command__title">{{ __('Race overview') }}</h2>
            <p class="profile-command__subtitle">{{ __('Your tournament stats and recent activity.') }}</p>
        </div>
    </header>

    <dl class="profile-command__stats">
        <div class="profile-command__stat">
            <dt class="profile-command__stat-label">{{ __('Tournaments') }}</dt>
            <dd class="profile-command__stat-value">{{ $stats['registered'] ?? 0 }}</dd>
        </div>
        <div class="profile-command__stat">
            <dt class="profile-command__stat-label">{{ __('Published results') }}</dt>
            <dd class="profile-command__stat-value">{{ $stats['results'] ?? 0 }}</dd>
        </div>
        <div class="profile-command__stat">
            <dt class="profile-command__stat-label">{{ __('Best finish') }}</dt>
            <dd class="profile-command__stat-value">
                @if (! is_null($stats['best_finish'] ?? null))
                    #{{ $stats['best_finish'] }}
                @else
                    —
                @endif
            </dd>
        </div>
        <div class="profile-command__stat">
            <dt class="profile-command__stat-label">{{ __('Upcoming') }}</dt>
            <dd class="profile-command__stat-value">{{ $stats['upcoming'] ?? 0 }}</dd>
        </div>
    </dl>

    @if ($latestResult)
        <div class="profile-command__latest">
            <div class="profile-command__latest-body">
                <p class="profile-command__stat-label">{{ __('Latest result') }}</p>
                <p class="profile-command__latest-title">{{ $latestResult['tournament_name'] }}</p>
                <p class="profile-command__latest-meta">
                    @if (! is_null($latestResult['position']))
                        {{ __('Position') }} #{{ $latestResult['position'] }}
                    @else
                        {{ __('Pending') }}
                    @endif
                    @if (! empty($latestResult['time_taken']))
                        <span aria-hidden="true"> · </span>{{ __('Time') }} {{ $latestResult['time_taken'] }}
                    @endif
                </p>
            </div>
            <a href="{{ route('leaderboard.index') }}" class="profile-command__link">
                {{ __('Leaderboard') }}
            </a>
        </div>
    @endif

    <div class="profile-command__panels">
        <article class="profile-command__panel" aria-labelledby="profile-command-tournaments-title">
            <div class="profile-command__panel-head">
                <h3 id="profile-command-tournaments-title">{{ __('My tournaments') }}</h3>
                <a href="{{ route('tournament.index') }}">{{ __('See all') }}</a>
            </div>
            <div class="profile-command__panel-body">
                @forelse ($tournaments as $tournament)
                    <div class="profile-command__row">
                        <div class="profile-command__row-main">
                            <p class="profile-command__row-title">{{ $tournament['title'] }}</p>
                            @if (! empty($tournament['meta']))
                                <p class="profile-command__row-meta">{{ $tournament['meta'] }}</p>
                            @endif
                        </div>
                        <span
                            @class([
                                'profile-command__badge',
                                'profile-command__badge--result' => $tournament['status'] === 'result',
                                'profile-command__badge--upcoming' => $tournament['status'] === 'upcoming',
                            ])
                            role="status"
                        >
                            {{ $tournament['status_label'] }}
                        </span>
                    </div>
                @empty
                    <div class="profile-command__empty">
                        <p>{{ __('No tournament registrations yet.') }}</p>
                        <a href="{{ route('tournament.index') }}" class="profile-command__empty-link">
                            {{ __('Browse tournaments') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </article>

        <article class="profile-command__panel" aria-labelledby="profile-command-results-title">
            <div class="profile-command__panel-head">
                <h3 id="profile-command-results-title">{{ __('Results history') }}</h3>
                <a href="{{ route('leaderboard.index') }}">{{ __('View standings') }}</a>
            </div>
            <div class="profile-command__panel-body">
                @forelse ($results as $result)
                    <div class="profile-command__row">
                        <div class="profile-command__row-main">
                            <p class="profile-command__row-title">{{ $result['title'] }}</p>
                            @if (! empty($result['meta']))
                                <p class="profile-command__row-meta">{{ $result['meta'] }}</p>
                            @endif
                        </div>
                        <span
                            @class(['profile-command__rank', 'profile-command__rank--'.$result['rank_class']])
                            aria-label="{{ __('Finish position') }}: {{ $result['position_label'] }}"
                        >
                            {{ $result['position_label'] }}
                        </span>
                    </div>
                @empty
                    <div class="profile-command__empty">
                        <p>{{ __('Results appear here once leaderboard entries are published.') }}</p>
                        <a href="{{ route('tournament.index') }}" class="profile-command__empty-link">
                            {{ __('Join a tournament') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </article>
    </div>
</section>

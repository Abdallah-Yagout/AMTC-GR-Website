<x-app-layout>
    @php
        $driverStats = $driverStats ?? [];
        $registrations = $registrations ?? collect();
        $resultsHistory = $resultsHistory ?? collect();
        $latestResult = $latestResult ?? null;
        $gamePoints = (int) ($gamePoints ?? 0);
        $gameRank = $gameRank ?? null;
        $gameTierTrack = $gameTierTrack ?? \App\Support\GamePointsTierProgress::build($gamePoints);
        $currentTier = $gameTierTrack['tiers'][$gameTierTrack['current_index'] - 1];
        $engagementHub = $engagementHub ?? app(\App\Services\EngagementHubService::class)->hubPayload(auth()->user());
    @endphp

    <div class="profile-page-shell">
        <div class="mx-auto max-w-7xl py-8 sm:px-6 sm:py-10 lg:px-8">
            <header class="profile-hero-panel relative mb-8 overflow-hidden rounded-xl border border-zinc-700 bg-zinc-900/95 px-6 py-8 shadow-lg shadow-black/25 sm:px-10">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent"></div>
                <div class="pointer-events-none absolute left-0 top-0 h-full w-1 bg-primary sm:w-1.5"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:gap-8">
                    <div class="profile-hero-identity flex shrink-0 flex-row items-center justify-center gap-4 sm:justify-start sm:gap-5">
                        <div class="relative mx-auto size-24 sm:mx-0 sm:size-28">
                            <span class="absolute inset-0 rounded-full ring-2 ring-primary/60 ring-offset-4 ring-offset-zinc-900"></span>
                            <img
                                src="{{ auth()->user()->profile_photo_url }}"
                                alt="{{ auth()->user()->name }}"
                                class="relative size-full rounded-full object-cover"
                            >
                        </div>
                        <figure class="profile-hero-current-tier">
                            <img
                                class="profile-hero-current-tier-img"
                                src="{{ tier_badge_image_url($currentTier['badge']) }}"
                                alt=""
                                loading="lazy"
                                decoding="async"
                                width="72"
                                height="80"
                            >
                            <figcaption class="profile-hero-current-tier-caption">
                                <span class="profile-hero-current-tier-kicker">{{ __('Current tier') }}</span>
                                <span class="profile-hero-current-tier-name">{{ __($currentTier['label']) }}</span>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="min-w-0 flex-1 text-center sm:text-start">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-primary">{{ __('Driver profile') }}</p>
                        <h1 class="mt-2 font-changa text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-1 text-sm text-zinc-400">{{ auth()->user()->email }}</p>
                        <p class="mt-3 max-w-xl text-sm leading-relaxed text-zinc-500">
                            {{ __('GR GT Cup command center for your registrations, race history, and profile setup.') }}
                        </p>
                    </div>
                    <div class="profile-hero-chip-wrap">
                        <span class="profile-hero-chip">{{ __('Racing Identity Active') }}</span>
                        <a href="{{ route('games.index') }}" class="profile-hero-chip profile-hero-chip-muted profile-hero-chip-link">
                            {{ __('Games points') }}: {{ number_format($gamePoints) }}
                            @if (! is_null($gameRank))
                                <span class="profile-hero-chip-sep">·</span> {{ __('Rank') }} #{{ $gameRank }}
                            @endif
                        </a>
                    </div>
                </div>
            </header>

            <section class="profile-ui-dashboard mb-8">
                <div class="profile-ui-command">
                    <div class="profile-ui-command-head">
                        <h2>{{ __('Driver Command Center') }}</h2>
                        <p>{{ __('Fast overview of your tournament journey and latest competitive output.') }}</p>
                    </div>

                    <div class="profile-ui-kpis">
                        <article class="profile-ui-kpi profile-ui-kpi-registered">
                            <p class="profile-ui-kpi-label">{{ __('Registered Tournaments') }}</p>
                            <p class="profile-ui-kpi-value">{{ $driverStats['total_registered'] ?? 0 }}</p>
                        </article>
                        <article class="profile-ui-kpi profile-ui-kpi-results">
                            <p class="profile-ui-kpi-label">{{ __('Results Published') }}</p>
                            <p class="profile-ui-kpi-value">{{ $driverStats['results_published'] ?? 0 }}</p>
                        </article>
                        <article class="profile-ui-kpi profile-ui-kpi-best">
                            <p class="profile-ui-kpi-label">{{ __('Best Finish') }}</p>
                            <p class="profile-ui-kpi-value">
                                @if (!is_null($driverStats['best_finish'] ?? null))
                                    #{{ $driverStats['best_finish'] }}
                                @else
                                    --
                                @endif
                            </p>
                        </article>
                        <article class="profile-ui-kpi profile-ui-kpi-upcoming">
                            <p class="profile-ui-kpi-label">{{ __('Upcoming Events') }}</p>
                            <p class="profile-ui-kpi-value">{{ $driverStats['upcoming_count'] ?? 0 }}</p>
                        </article>
                        <a href="{{ route('games.index') }}" class="profile-ui-kpi profile-ui-kpi-games profile-ui-kpi-linkcard">
                            <p class="profile-ui-kpi-label">{{ __('Games Points') }}</p>
                            <p class="profile-ui-kpi-value">{{ number_format($gamePoints) }}</p>
                            @if (! is_null($gameRank))
                                <p class="profile-ui-kpi-meta">{{ __('Games board') }} · #{{ $gameRank }}</p>
                            @else
                                <p class="profile-ui-kpi-meta">{{ __('Claim daily & complete tasks on the Games page.') }}</p>
                            @endif
                        </a>
                    </div>

                    <div class="profile-ui-latest-row">
                        <div class="profile-ui-latest-content">
                            <p class="profile-ui-kpi-label">{{ __('Latest Result Snapshot') }}</p>
                            @if ($latestResult)
                                <p class="profile-ui-latest-title">{{ $latestResult['tournament_name'] ?? __('Tournament') }}</p>
                                <p class="profile-ui-latest-meta">
                                    {{ __('Position') }}:
                                    @if (!is_null($latestResult['position'] ?? null))
                                        #{{ $latestResult['position'] }}
                                    @else
                                        {{ __('Pending') }}
                                    @endif
                                    @if (!empty($latestResult['time_taken']))
                                        <span>- {{ __('Time') }} {{ $latestResult['time_taken'] }}</span>
                                    @endif
                                </p>
                            @else
                                <p class="profile-ui-latest-meta">{{ __('No official result yet. Keep racing to unlock this panel.') }}</p>
                            @endif
                        </div>

                        <div class="profile-ui-mini-bars" aria-hidden="true">
                            @foreach (range(1, 9) as $bar)
                                <span style="height: {{ 18 + (($bar * 11) % 30) }}px;"></span>
                            @endforeach
                        </div>

                        <a href="{{ route('leaderboard.index') }}" class="profile-ui-leaderboard-btn">
                            {{ __('Open Leaderboard') }}
                        </a>
                    </div>
                </div>

                <div class="profile-ui-panels">
                    <article class="profile-ui-panel">
                        <div class="profile-ui-panel-head">
                            <h3>{{ __('My Tournaments') }}</h3>
                            <a href="{{ route('tournament.index') }}">{{ __('See all') }}</a>
                        </div>

                        <div class="profile-ui-panel-body">
                            @forelse ($registrations->take(5) as $registration)
                                @php
                                    $tournament = $registration->tournament;
                                    $dateLabel = null;
                                    if (!empty($tournament?->start_date)) {
                                        $dateLabel = \Illuminate\Support\Carbon::parse($tournament->start_date)->format('d M Y');
                                    }
                                    $hasResult = $registration->leaderboards->contains(function ($leaderboard) {
                                        $pivot = $leaderboard->pivot;

                                        return !is_null($pivot?->position) || !is_null($pivot?->time_taken) || !is_null($pivot?->status);
                                    });
                                @endphp
                                <div class="profile-ui-row">
                                    <div class="profile-ui-row-main">
                                        <p class="profile-ui-row-title">{{ $tournament?->title ?? __('Tournament') }}</p>
                                        <p class="profile-ui-row-meta">
                                            @if ($dateLabel)
                                                <span>{{ $dateLabel }}</span>
                                            @endif
                                            @if (!empty($registration->location))
                                                <span>- {{ $registration->location }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="profile-ui-status {{ $hasResult ? 'is-result' : 'is-upcoming' }}">
                                        {{ $hasResult ? __('Result Published') : __('Upcoming') }}
                                    </span>
                                </div>
                            @empty
                                <p class="profile-ui-empty">{{ __('No tournament registration yet. Join your first event and start building your legacy.') }}</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="profile-ui-panel">
                        <div class="profile-ui-panel-head">
                            <h3>{{ __('Results History') }}</h3>
                            <a href="{{ route('leaderboard.index') }}">{{ __('View standings') }}</a>
                        </div>

                        <div class="profile-ui-panel-body">
                            @forelse ($resultsHistory->take(5) as $result)
                                @php
                                    $rank = $result['position'] ?? null;
                                    $rankClass = 'is-bronze';
                                    if ($rank === 1) {
                                        $rankClass = 'is-gold';
                                    } elseif ($rank === 2) {
                                        $rankClass = 'is-silver';
                                    }
                                @endphp
                                <div class="profile-ui-row">
                                    <div class="profile-ui-row-main">
                                        <p class="profile-ui-row-title">{{ $result['tournament_name'] ?? __('Tournament') }}</p>
                                        <p class="profile-ui-row-meta">
                                            @if (!empty($result['location']))
                                                <span>{{ $result['location'] }}</span>
                                            @endif
                                            @if (!empty($result['time_taken']))
                                                <span>- {{ __('Time') }}: {{ $result['time_taken'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="profile-ui-rank {{ $rankClass }}">
                                        @if (!is_null($rank))
                                            #{{ $rank }}
                                        @else
                                            --
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <p class="profile-ui-empty">{{ __('No official results yet. Once leaderboard entries are published, they will appear here.') }}</p>
                            @endforelse
                        </div>
                    </article>
                </div>
            </section>

            @include('profile.partials.game-points-tier-track', ['gameTierTrack' => $gameTierTrack, 'gamePoints' => $gamePoints])

            @include('profile.partials.engagement-hub', ['engagementHub' => $engagementHub])

            <x-profile-divider />

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('basic-info')
                <x-profile-divider />
            @endif

            @livewire('contact-info')
            <x-profile-divider />

            @livewire('gaming-experience')
            <x-profile-divider />
            @livewire('game-preferences')
            <x-profile-divider />
            @livewire('toyota-g-r-knowledge')
            <x-profile-divider />
            @livewire('tournament-experience')
            <x-profile-divider />
            @livewire('additional-information')

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <x-profile-divider />
                <div class="sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <x-profile-divider />
                <div class="sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            <x-profile-divider />

            <div class="sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-profile-divider />
                <div class="sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

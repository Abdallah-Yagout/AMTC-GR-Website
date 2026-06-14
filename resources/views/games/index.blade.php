<x-app-layout>
    @php
        $gameRewards = $gameRewards ?? ['profile_complete' => 200, 'image_upload' => 40, 'daily_claim' => 10];
    @endphp
    <section class="games-page px-4 py-10 sm:px-6 lg:px-8">
        <div class="games-shell max-w-6xl mx-auto">
            <div class="games-hero">
                <p class="games-kicker">{{ __('Arcade Zone') }}</p>
                <h1 class="games-title">{{ __('Games') }}</h1>
                <p class="games-subtitle">{{ __('Play arcade titles, earn Games points, claim your daily bonus, and climb the board—all in one place.') }}</p>
            </div>

            <div
                class="games-body"
                x-data="{ activeTab: @js($activeTab) }"
                x-cloak
            >
                <div class="games-tabs" role="tablist" aria-label="{{ __('Games sections') }}">
                    <button
                        type="button"
                        role="tab"
                        id="games-tab-hub"
                        :aria-selected="activeTab === 'hub'"
                        :tabindex="activeTab === 'hub' ? 0 : -1"
                        class="games-tab"
                        :class="{ 'is-active': activeTab === 'hub' }"
                        @click="activeTab = 'hub'"
                    >
                        {{ __('Arcade') }}
                    </button>
                    <button
                        type="button"
                        role="tab"
                        id="games-tab-board"
                        :aria-selected="activeTab === 'board'"
                        :tabindex="activeTab === 'board' ? 0 : -1"
                        class="games-tab"
                        :class="{ 'is-active': activeTab === 'board' }"
                        @click="activeTab = 'board'"
                    >
                        {{ __('Games Board') }}
                    </button>
                </div>

                <div
                    x-show="activeTab === 'hub'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    role="tabpanel"
                    aria-labelledby="games-tab-hub"
                    class="games-panel"
                >
                    <div class="games-grid">
                        @php
                            $playableArcade = ($arcadeGames ?? collect())->filter(fn ($g) => $g->playUrl());
                        @endphp
                        @forelse ($playableArcade as $game)
                            @php($arcadeCover = $game->hubCoverImageUrl())
                            <article @class(['games-card', 'games-card--arcade', 'games-card--arcade-has-cover' => $arcadeCover])>
                                @if ($arcadeCover)
                                    <div class="games-arcade-cover-wrap">
                                        <img
                                            src="{{ $arcadeCover }}"
                                            alt="{{ $game->name }}"
                                            class="games-arcade-cover"
                                            width="800"
                                            height="450"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    </div>
                                @endif
                                <div class="games-arcade-inner">
                                    <h2>{{ $game->name }}</h2>
                                    @if ($game->description)
                                        <p>{{ $game->description }}</p>
                                    @endif
                                    @if ($game->reward_type === \App\Models\GameDefinition::REWARD_POINTS && $game->points_per_completion > 0)
                                        <p class="games-arcade-meta">
                                            {{ __('Reward') }}: +{{ number_format($game->points_per_completion) }}
                                            · {{ __('Cooldown') }}: {{ $game->points_cooldown_hours }} {{ __('hours') }}
                                        </p>
                                    @endif
                                    <a href="{{ $game->playUrl() }}" class="games-arcade-play">{{ __('Play') }}</a>
                                </div>
                            </article>
                        @empty
                            <article class="games-card games-card--soon">
                                <div class="games-card-badge">{{ __('Coming soon') }}</div>
                                <h2>{{ __('Mini-games') }}</h2>
                                <p>{{ __('New browser games will land here. Complete your profile, add your photo, and stack points so you are ready when the next title drops.') }}</p>
                            </article>
                        @endforelse

                        <article class="games-card games-points-card">
                            <h2>{{ __('Your Games Points') }}</h2>
                            @auth
                                <p class="games-points-total">{{ number_format((int) optional($stat)->points) }}</p>
                                <ul class="games-points-rules">
                                    <li>{{ __('Complete profile') }}: <strong>+{{ number_format($gameRewards['profile_complete']) }}</strong></li>
                                    <li>{{ __('Upload profile image') }}: <strong>+{{ number_format($gameRewards['image_upload']) }}</strong></li>
                                    <li>{{ __('Daily claim') }}: <strong>+{{ number_format($gameRewards['daily_claim']) }}</strong></li>
                                </ul>

                                @if(session('games_success'))
                                    <div class="games-alert games-alert-success">{{ session('games_success') }}</div>
                                @endif
                                @if(session('games_error'))
                                    <div class="games-alert games-alert-error">{{ session('games_error') }}</div>
                                @endif

                                <form method="POST" action="{{ route('games.claim-daily') }}">
                                    @csrf
                                    <button type="submit" class="games-claim-btn" {{ $claimedToday ? 'disabled' : '' }}>
                                        @if ($claimedToday)
                                            {{ __('Claimed Today') }}
                                        @else
                                            {{ __('Claim daily') }} +{{ number_format($gameRewards['daily_claim']) }}
                                        @endif
                                    </button>
                                </form>
                            @else
                                <p>{{ __('Sign in to start earning Games points and claim your daily bonus.') }}</p>
                                <a href="{{ route('login') }}" class="games-link">{{ __('Sign in') }}</a>
                            @endauth
                        </article>
                    </div>
                </div>

                <div
                    x-show="activeTab === 'board'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    role="tabpanel"
                    aria-labelledby="games-tab-board"
                    class="games-panel games-panel--board"
                >
                    <div class="games-board-intro">
                        <p class="games-board-lead">{{ __('Games-only ranking') }}</p>
                        <p class="games-board-hint">{{ __('Separate from race / tournament leaderboard. Sorted by Games points.') }}</p>
                        @auth
                            @if(! is_null($viewerRank))
                                <p class="games-board-you">{{ __('Your rank') }}: <strong>#{{ $viewerRank }}</strong></p>
                            @endif
                        @endauth
                    </div>
                    <div class="games-table-wrap">
                        @include('games.partials.leaderboard-table', ['leaders' => $leaders])
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>

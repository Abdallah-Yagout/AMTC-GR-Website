<x-app-layout>
    <div
        id="matching-game-root"
        class="game-matching-page game-matching-compact"
        data-authed="{{ auth()->check() ? '1' : '0' }}"
        data-start-url="{{ route('games.matching.start') }}"
        data-complete-url="{{ route('games.matching.complete') }}"
        data-msg-loading="{{ __('Preparing deck…') }}"
        data-msg-start="{{ __('Flip cards to find pairs.') }}"
        data-msg-won="{{ __('Board cleared!') }}"
        data-msg-err="{{ __('Could not verify game. Try again.') }}"
        data-msg-cooldown="{{ __('Great run! Points for this game can only be earned once per cooldown — play again anytime.') }}"
        data-msg-points="{{ __('You earned :pts Games points!') }}"
        data-msg-need-login="{{ __('Sign in to play and earn Games points.') }}"
        data-msg-cooldown-label="{{ __('Next points reward') }}"
        data-cover-url="{{ asset('images/games/matching/gr-emblem.png') }}"
        data-face-urls='@json(collect(range(1, 10))->map(fn (int $i): string => asset("images/games/matching/gr-{$i}.png"))->values()->all())'
    >
        <header class="game-matching-hero">
            <div class="game-matching-hero-accent" aria-hidden="true"></div>
            <div class="game-matching-hero-inner max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
                <a href="{{ route('games.index') }}" class="game-matching-back">{{ __('Back to Arcade') }}</a>
                <p class="game-matching-kicker">{{ __('Arcade') }}</p>
                <h1 class="game-matching-title">{{ $definition->name }}</h1>
                @if($definition->description)
                    <p class="game-matching-lead">{{ $definition->description }}</p>
                @endif
                <div class="game-matching-meta">
                    @if($definition->reward_type === \App\Models\GameDefinition::REWARD_POINTS && $definition->points_per_completion > 0)
                        <span class="game-matching-chip">
                            {{ __('Reward') }}: +{{ number_format($definition->points_per_completion) }} {{ __('pts') }}
                        </span>
                        <span class="game-matching-chip">
                            {{ __('Cooldown') }}: {{ $definition->points_cooldown_hours }} {{ __('hours') }}
                        </span>
                    @endif
                </div>
            </div>
        </header>

        <div class="game-matching-body max-w-5xl mx-auto px-4 sm:px-6 py-8">
            @auth
                <div class="game-matching-cooldown" data-matching-cooldown hidden>
                    <div class="game-matching-cooldown-head">
                        <span class="game-matching-cooldown-label" data-matching-cooldown-label></span>
                        <span class="game-matching-cooldown-timer" data-matching-cooldown-timer aria-live="polite">--:--:--</span>
                    </div>
                    <div class="game-matching-cooldown-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-matching-cooldown-track>
                        <div class="game-matching-cooldown-fill" data-matching-cooldown-fill></div>
                    </div>
                </div>
                <div class="game-matching-toolbar">
                    <p class="game-matching-status" data-matching-status>{{ __('Loading…') }}</p>
                    <button type="button" class="game-matching-new-btn" data-matching-new>{{ __('New game') }}</button>
                </div>
                <div class="game-matching-grid-wrap">
                    <div class="game-matching-grid" data-matching-grid role="grid" aria-label="{{ __('Card grid') }}"></div>
                </div>
            @else
                <div class="game-matching-guest">
                    <p class="game-matching-guest-text">{{ __('Sign in to play and earn Games points.') }}</p>
                    <a href="{{ route('login') }}" class="game-matching-guest-btn">{{ __('Sign in') }}</a>
                </div>
            @endauth
        </div>

        <div class="game-matching-overlay" data-matching-overlay hidden>
            <div class="game-matching-overlay-panel">
                <h2 class="game-matching-overlay-title" data-matching-overlay-title></h2>
                <p class="game-matching-overlay-body" data-matching-overlay-body></p>
                <button type="button" class="game-matching-overlay-close" data-matching-overlay-close>{{ __('Close') }}</button>
            </div>
        </div>
    </div>

    @auth
        @push('js')
            @vite('resources/js/games/matching/main.js')
        @endpush
    @endauth
</x-app-layout>

<footer class="site-footer">
    <div class="site-footer-accent" aria-hidden="true"></div>
    <div class="site-footer-grid" aria-hidden="true"></div>

    <div class="site-footer-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="site-footer-columns">
            <div class="site-footer-col site-footer-brand">
                <a href="{{ route('home') }}" class="site-footer-logo-lockup">
                    <img
                        class="site-footer-logo-gtcup"
                        src="{{ asset('img/gtcup-splash-logo.png') }}"
                        alt="{{ __('Toyota Gazoo Racing GT Cup') }}"
                        width="120"
                        loading="lazy"
                    >
                    <img
                        class="site-footer-logo-toyota"
                        src="{{ asset('img/logo.png') }}"
                        alt="{{ __('Toyota') }}"
                        width="140"
                        loading="lazy"
                    >
                </a>
                <p class="site-footer-tagline">
                    {{ __('Toyota Gazoo Racing — precision, passion, and the spirit of the GT Cup.') }}
                </p>
                <p class="site-footer-amtc">{{ __('TOYOTA AMTC') }}</p>
            </div>

            <div class="site-footer-col">
                <h2 class="site-footer-heading">{{ __('Explore') }}</h2>
                <ul class="site-footer-links">
                    <li>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('forum.index') }}">{{ __('Community') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('news.index') }}">{{ __('News') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('games.index') }}">{{ __('Games') }}</a>
                    </li>
                </ul>
            </div>

            <div class="site-footer-col">
                <h2 class="site-footer-heading">{{ __('Racing hub') }}</h2>
                <ul class="site-footer-links">
                    <li>
                        <a href="{{ route('tournament.index') }}">{{ __('Tournament') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('leaderboard.index') }}">{{ __('Leaderboard') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('gr-cars.index') }}" class="site-footer-nowrap">{{ __('GR-CARS') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('contact.index') }}">{{ __('Contact') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="site-footer-divider" role="presentation"></div>

        <div class="site-footer-bottom">
            <p class="site-footer-powered">
                <a href="https://arkadia.dev/" rel="noopener noreferrer" target="_blank">
                    {{ __('Powered by') }} {{ __('Arkadia Studio') }}
                </a>
            </p>
            <p class="site-footer-copy">
                {{ __('© :year TOYOTA MOTOR CORPORATION. All Rights Reserved.', ['year' => date('Y')]) }}
            </p>
        </div>
    </div>
</footer>

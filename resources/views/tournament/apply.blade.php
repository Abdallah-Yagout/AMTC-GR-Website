<x-app-layout>
    <div class="tournament-apply-page">
        <header class="tournament-directory-hero tournament-apply-hero">
            <div class="tournament-directory-hero-accent" aria-hidden="true"></div>
            <div class="tournament-directory-hero-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
                <p class="tournament-directory-kicker">{{ __('Toyota Gazoo Racing') }}</p>
                <h1 class="tournament-directory-title">{{ __('Join the Competition') }}</h1>
                <p class="tournament-directory-lead tournament-apply-hero-lead">{{ __('Submit your entry and monitor your progress — full speed ahead!') }}</p>
            </div>
        </header>

        @if ($errors->any())
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="tournament-apply-errors" role="alert">
                    <p class="tournament-apply-errors-title">{{ __('Please fix the following:') }}</p>
                    <ul class="tournament-apply-errors-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="tournament-apply-body max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
            <div class="tournament-apply-panel">
                <h2 class="tournament-apply-panel-title">{{ __('Participant Application') }}</h2>

                @guest
                    <div class="tournament-apply-guest text-center py-6 sm:py-8">
                        <p class="text-lg mb-4 text-white/90">{{ __('You need to be logged in to submit an application.') }}</p>
                        <a href="{{ route('login') }}" class="tournament-directory-cta tournament-apply-inline-cta">
                            {{ __('Login to Apply') }}
                        </a>
                        <p class="mt-6 text-zinc-400 text-sm">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="tournament-apply-link">{{ __('Register here') }}</a>
                        </p>
                    </div>
                @else
                    @if($hasSubmitted)
                        <div class="tournament-apply-success">
                            <p class="font-bold text-emerald-200">{{ __('You have already submitted your application for this tournament!') }}</p>
                            <p class="mt-1 text-emerald-100/90">{{ __('We look forward to seeing you at the event.') }}</p>
                        </div>
                    @else
                        <form action="{{ route('tournament.submit') }}" method="post" class="tournament-apply-form">
                            <input type="hidden" name="tournamentId" value="{{ $tournament->id }}">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="tournament-apply-label" for="apply-name">{{ __('Name') }}</label>
                                    <input
                                        id="apply-name"
                                        type="text"
                                        name="name"
                                        required
                                        readonly
                                        value="{{ auth()->user()->name }}"
                                        placeholder="{{ __('Enter your name') }}"
                                        class="tournament-apply-input"
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-4">
                                <div class="space-y-1 md:col-span-1">
                                    <label class="tournament-apply-label" for="apply-gender">{{ __('Gender') }}</label>
                                    <input
                                        id="apply-gender"
                                        type="text"
                                        readonly
                                        class="tournament-apply-input"
                                        name="gender"
                                        value="{{ auth()->user()->profile->gender ?? '' }}"
                                    >
                                </div>

                                <div class="space-y-1 md:col-span-2">
                                    <label class="tournament-apply-label" for="apply-city">{{ __('City') }}</label>
                                    <input
                                        id="apply-city"
                                        type="text"
                                        readonly
                                        class="tournament-apply-input"
                                        name="city"
                                        value="{{ auth()->user()->profile->city ?? '' }}"
                                    >
                                </div>

                                <div class="space-y-1 md:col-span-3">
                                    <label class="tournament-apply-label" for="apply-phone">{{ __('Phone') }}</label>
                                    <input
                                        id="apply-phone"
                                        type="text"
                                        name="phone"
                                        required
                                        readonly
                                        value="{{ auth()->user()->phone }}"
                                        placeholder="{{ __('Phone number') }}"
                                        class="tournament-apply-input"
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div class="space-y-1">
                                    <label class="tournament-apply-label" for="apply-email">{{ __('Email Address') }}</label>
                                    <input
                                        id="apply-email"
                                        type="email"
                                        name="email"
                                        required
                                        readonly
                                        value="{{ auth()->user()->email }}"
                                        placeholder="{{ __('Enter your email address') }}"
                                        class="tournament-apply-input"
                                    >
                                </div>
                            </div>

                            <button type="submit" class="tournament-directory-cta tournament-apply-submit mt-8">
                                {{ __('Submit') }}
                            </button>
                        </form>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</x-app-layout>

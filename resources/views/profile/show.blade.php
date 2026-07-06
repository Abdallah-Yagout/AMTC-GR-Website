<x-app-layout>
    @php
        $gamePoints = (int) ($gamePoints ?? 0);
        $gameRank = $gameRank ?? null;
        $gameTierTrack = $gameTierTrack ?? \App\Support\GamePointsTierProgress::build($gamePoints);
        $engagementHub = $engagementHub ?? app(\App\Services\EngagementHubService::class)->hubPayload(auth()->user());
        $commandCenter = $commandCenter ?? [
            'stats' => ['registered' => 0, 'results' => 0, 'best_finish' => null, 'upcoming' => 0],
            'latest_result' => null,
            'tournaments' => [],
            'results' => [],
        ];
    @endphp

    <div class="profile-page-shell">
        <div class="mx-auto max-w-7xl py-8 sm:px-6 sm:py-10 lg:px-8">
            <header class="profile-hero-panel relative mb-8 overflow-hidden rounded-xl border border-zinc-700 bg-zinc-900/95 px-6 py-8 shadow-lg shadow-black/25 sm:px-10">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent"></div>
                <div class="pointer-events-none absolute left-0 top-0 h-full w-1 bg-primary sm:w-1.5"></div>

                <div class="relative">
                    @livewire('profile-completion-banner')

                    <div class="profile-hero-panel-divider" aria-hidden="true"></div>

                    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:gap-8">
                    <div class="profile-hero-identity flex shrink-0 justify-center sm:justify-start">
                        <div class="relative mx-auto size-24 sm:mx-0 sm:size-28">
                            <span class="absolute inset-0 rounded-full ring-2 ring-primary/60 ring-offset-4 ring-offset-zinc-900"></span>
                            <img
                                src="{{ auth()->user()->profile_photo_url }}"
                                alt="{{ auth()->user()->name }}"
                                class="relative size-full rounded-full object-cover"
                            >
                        </div>
                    </div>
                    <div class="min-w-0 flex-1 text-center sm:text-start">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-primary">{{ __('Driver profile') }}</p>
                        <h1 class="mt-2 {{ app()->getLocale() === 'ar' ? 'font-cairo' : 'font-changa' }} text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-1 text-sm text-zinc-400">{{ auth()->user()->email }}</p>
                        <p class="mt-3 max-w-xl text-sm leading-relaxed text-zinc-500">
                            {{ __('Your registrations, race history, and profile settings.') }}
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
                </div>
            </header>

            @include('profile.partials.driver-command-center', ['commandCenter' => $commandCenter])

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

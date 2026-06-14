<x-app-layout>
    <div class="tournament-directory-page">
        <header class="tournament-directory-hero">
            <div class="tournament-directory-hero-accent" aria-hidden="true"></div>
            <div class="tournament-directory-hero-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
                <p class="tournament-directory-kicker">{{ __('Toyota Gazoo Racing') }}</p>
                <h1 class="tournament-directory-title">{{ __('Let the Race Begin!') }}</h1>
                <p class="tournament-directory-lead">{{ __('Check out upcoming racing events by year') }}</p>
            </div>
        </header>

        <section
            x-data="{ tab: '{{ $selectedYear }}' }"
            class="tournament-directory-body max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10"
        >
            <div class="tournament-directory-tabs flex flex-wrap gap-2 sm:gap-3 mb-8 overflow-x-auto pb-1" role="tablist" aria-label="{{ __('Tournament years') }}">
                @foreach($paginatedTournamentsByYear as $year => $paginator)
                    <a
                        href="?year={{ $year }}"
                        role="tab"
                        :aria-selected="tab === '{{ $year }}'"
                        @click.prevent="tab = '{{ $year }}'; updateUrl('{{ $year }}')"
                        :class="tab === '{{ $year }}' ? 'tournament-directory-tab is-active' : 'tournament-directory-tab'"
                        class="whitespace-nowrap"
                    >
                        {{ $year }}
                    </a>
                @endforeach
            </div>

            @foreach($paginatedTournamentsByYear as $year => $paginator)
                <div
                    x-show="tab === '{{ $year }}'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    role="tabpanel"
                >
                    <div class="tournament-directory-list space-y-4 sm:space-y-5">
                        @forelse($paginator->items() as $tournament)
                            <article class="tournament-directory-card">
                                <div class="tournament-directory-card-inner">
                                    <div class="tournament-directory-card-media">
                                        <img
                                            src="{{ \App\Support\GrStockImage::forStorage($tournament->image, 'tournament-list-'.$tournament->id) }}"
                                            alt="{{ $tournament->title }}"
                                            class="tournament-directory-card-img"
                                            loading="lazy"
                                        >
                                    </div>

                                    <div class="tournament-directory-card-main">
                                        <h3 class="tournament-directory-card-title">{{ $tournament->title }}</h3>

                                        <div class="tournament-directory-badges flex flex-wrap gap-2 mt-2">
                                            @foreach($tournament->location ?? [] as $loc)
                                                <span class="tournament-directory-badge">{{ __($loc) }}</span>
                                            @endforeach
                                        </div>

                                        <div class="tournament-directory-meta flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-sm text-zinc-400">
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="far fa-calendar-alt text-zinc-500" aria-hidden="true"></i>
                                                {{ \Carbon\Carbon::parse($tournament->start_date)->format('M j, Y') }}
                                            </span>
                                        </div>

                                        <p class="tournament-directory-desc mt-2 line-clamp-2 sm:line-clamp-3">
                                            {{ $tournament->description }}
                                        </p>
                                    </div>

                                    <div class="tournament-directory-card-actions">
                                        @if(!$tournament->status)
                                            <span class="tournament-directory-cta tournament-directory-cta--disabled">
                                                {{ __('Full') }}
                                            </span>
                                        @else
                                            <a href="{{ route('tournament.apply', ['id' => $tournament->id]) }}" class="tournament-directory-cta">
                                                {{ __('Register') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="tournament-directory-empty">
                                <p>{{ __('No tournaments scheduled for :year yet.', ['year' => $year]) }}</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="tournament-directory-pagination mt-8">
                        {{ $paginator->appends(['year' => $year])->links() }}
                    </div>
                </div>
            @endforeach
        </section>
    </div>

    @push('js')
        <script>
            function updateUrl(year) {
                const url = new URL(window.location);
                url.searchParams.set('year', year);
                url.searchParams.delete('page');
                history.pushState(null, '', url);
            }
        </script>
    @endpush
</x-app-layout>

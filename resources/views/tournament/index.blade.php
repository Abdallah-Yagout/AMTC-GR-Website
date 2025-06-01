<x-app-layout>
    <section class="bg-primary-200 px-10 py-10 text-white">
        <h1 class="text-3xl font-bold mb-2">{{ __('Let the Race Begin!') }}</h1>
        <p class="text-lg">{{__('Check out upcoming racing events by year')}}</p>
    </section>

    <section x-data="{ tab: '{{ $selectedYear }}' }" class="px-10 bg-black py-6">
        <!-- Year Tabs -->
        <div class="flex space-x-4 mb-6 overflow-x-auto pb-2">
            @foreach($paginatedTournamentsByYear as $year => $paginator)
                <a href="?year={{ $year }}"
                   @click.prevent="tab = '{{ $year }}'; updateUrl('{{ $year }}')"
                   :class="tab === '{{ $year }}'
                        ? 'border-b-4 border-primary text-red-600 font-semibold'
                        : 'text-white border-transparent'"
                   class="py-2 px-4 transition whitespace-nowrap cursor-pointer">
                    {{ $year }}
                </a>
            @endforeach
        </div>

        <!-- Tournament List by Year -->
        @foreach($paginatedTournamentsByYear as $year => $paginator)
            <div x-show="tab === '{{ $year }}'" class="space-y-3 sm:space-y-4">
                @forelse($paginator->items() as $tournament)
                    <div class="p-3 sm:p-4 shadow-sm bg-secondary-100 text-white rounded-lg">
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center">
                            <!-- Tournament Image -->
                            @if(!empty($tournament->image))
                                <div class="w-full sm:w-40 h-32 flex-shrink-0 overflow-hidden rounded-lg">
                                    <img src="{{ asset('storage/' . $tournament->image) }}"
                                         alt="{{ $tournament->title }}"
                                         class="object-cover w-full h-full rounded-lg shadow-sm border border-gray-600">
                                </div>
                            @endif

                            <!-- Tournament Info -->
                            <div class="flex-1">
                                <h3 class="text-lg sm:text-xl font-semibold line-clamp-2">{{ $tournament->title }}</h3>

                                <!-- Location Badges -->
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    @foreach($tournament->location ?? [] as $loc)
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-200 leading-tight">
                                            {{ __($loc) }}
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Date -->
                                <div class="flex items-center gap-4 mt-2 text-xs sm:text-sm text-gray-400">
                                    <span>
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($tournament->start_date)->format('M j, Y') }}
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-xs sm:text-sm text-gray-400 mt-1 line-clamp-2 sm:line-clamp-3">
                                    {{ $tournament->description }}
                                </p>
                            </div>

                            <!-- Register Button -->
                            <div class="sm:w-auto w-full mt-3 sm:mt-0">
                                @if(!$tournament->status)
                                    <span class="block text-center sm:inline-block text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-1 bg-gray-400 text-white rounded cursor-not-allowed">
                                        {{ __('Full') }}
                                    </span>
                                @else
                                    <a href="{{ route('tournament.apply', ['id' => $tournament->id]) }}"
                                       class="block text-center sm:inline-block text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-1 bg-red-600 text-white rounded hover:bg-red-700 transition whitespace-nowrap">
                                        {{ __('Register') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm sm:text-base text-gray-400 p-4 text-center">
                        No tournaments scheduled for {{ $year }} yet.
                    </p>
                @endforelse

                <!-- Pagination for this year -->
                <div class="mt-6">
                    {{ $paginator->appends(['year' => $year])->links() }}
                </div>
            </div>
        @endforeach
    </section>

    @push('js')
        <script>
            function updateUrl(year) {
                const url = new URL(window.location);
                url.searchParams.set('year', year);
                url.searchParams.delete('page'); // Reset to page 1
                history.pushState(null, '', url);
            }
        </script>
    @endpush
</x-app-layout>

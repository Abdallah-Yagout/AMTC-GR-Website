<x-app-layout>
    <section class="dark:bg-primary-200 p-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">{{ __('Who\'s in the lead?') }}</h1>
        <p class="text-lg">{{ __('See who\'s ahead, who\'s catching up, and who\'s next to take the podium') }}</p>
    </section>

    <!-- Year Tabs -->
    <div class="container mx-auto px-4 pt-6">
        <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide border-b border-gray-700">
            @foreach($availableYears as $year)
                <a href="?year={{ $year }}"
                    @class([
                        'flex-shrink-0 py-2 px-4 transition whitespace-nowrap',
                        'border-b-4 border-primary text-red-600 font-semibold' => $year == $selectedYear,
                        'text-white border-transparent' => $year != $selectedYear,
                    ])>
                    {{ $year }}
                </a>
            @endforeach
        </div>
    </div>

    <section class="container mx-auto px-4 py-8">
        @if(isset($yearData[$selectedYear]['mainTournaments']) && $yearData[$selectedYear]['mainTournaments']->isNotEmpty())
            <div class="space-y-10">
                @foreach($yearData[$selectedYear]['mainTournaments'] as $tournamentPair)
                    <div class="space-y-6">
                        <!-- Tournament Row -->
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Main Tournament -->
                            <div class="lg:w-1/3 bg-secondary-100 p-6 rounded-lg shadow-lg">
                                <h1 class="text-white text-2xl font-bold mb-4">
                                    {{ $tournamentPair['main']->title }}
                                </h1>
                                @include('components.leaderboard-table', [
                                    'participants' => $tournamentPair['main']->leaderboards
                                ])
                            </div>

                            <!-- Location Leaderboards -->
                            @if(!empty($tournamentPair['locations']))
                                <div class="lg:w-1/3 space-y-4 overflow-y-auto max-h-[600px]">
                                    @foreach($tournamentPair['locations'] as $location => $participants)
                                        <div class="bg-primary-800 p-4 rounded-lg shadow">
                                            <h2 class="text-white text-xl font-semibold mb-2">
                                                {{ ucfirst($location) }} Leaderboard
                                            </h2>
                                            @include('components.leaderboard-table', [
                                                'participants' => $participants,
                                                'showDiff' => true
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Final Tournament -->
                            @if($tournamentPair['final'])
                                <div class="lg:w-1/3 bg-secondary-100 p-6 rounded-lg shadow-lg">
                                    <h2 class="text-white text-2xl font-bold mb-4">
                                        {{ $tournamentPair['final']->title }} - {{ __('Final Results') }}
                                    </h2>

                                    @include('components.leaderboard-table', [
                                        'participants' => $tournamentPair['final']->leaderboards
                                    ])
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Overall Season Leaderboard -->
            @if(isset($yearData[$selectedYear]['seasonAggregate']) && $yearData[$selectedYear]['seasonAggregate']->isNotEmpty())
                <div class="bg-secondary-100 p-6 rounded-lg shadow-lg mt-10">
                    <h1 class="text-white text-3xl font-bold mb-4">{{ __('Overall Season Leaderboard') }}</h1>
                    @include('components.leaderboard-table', [
                        'participants' => $yearData[$selectedYear]['seasonAggregate']
                    ])
                </div>
            @endif
        @else
            <!-- No tournaments message -->
            <div class="bg-secondary-100 p-6 rounded-lg shadow-lg text-center">
                <p class="text-white text-xl">{{ __('No tournaments found for') }} {{ $selectedYear }}</p>
            </div>
        @endif
    </section>
</x-app-layout>

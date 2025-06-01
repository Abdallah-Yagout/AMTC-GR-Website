<x-app-layout>
    <!-- Header Section -->
    <div class="w-full">
        <section class="dark:bg-primary-200 p-10 dark:text-white">
            <h1 class="text-3xl font-bold mb-2">{{ __('Who\'s in the lead?') }}</h1>
            <p class="text-lg">{{ __('See who\'s ahead, who\'s catching up, and who\'s next to take the podium') }}</p>
        </section>
    </div>

    <!-- Year Tabs Section -->
    <div class="w-full">
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
    </div>

    <!-- Main Content Section -->
    <div class="w-full">
        <section class="container mx-auto px-4 py-8">
            @if(isset($yearData[$selectedYear]['mainTournaments']) && $yearData[$selectedYear]['mainTournaments']->isNotEmpty())
                <div class="space-y-12"> <!-- Increased space between tournaments -->
                    @foreach($yearData[$selectedYear]['mainTournaments'] as $tournamentPair)
                        <div class="space-y-8">
                            <!-- Main Tournament Title -->
                            <h1 class="text-primary text-2xl font-bold">
                                {{ $tournamentPair['main']->title }}
                            </h1>

                            <!-- Location Leaderboards -->
                            @if(!empty($tournamentPair['locations']))
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($tournamentPair['locations'] as $location => $participants)
                                        <div class="bg-primary-800 p-4 rounded-lg shadow">
                                            <h2 class="text-secondary-300 text-lg font-semibold mb-2">
                                                {{ ucfirst($location) }} Leaderboard
                                            </h2>
                                            @include('components.leaderboard-table', [
                                                'participants' => $participants,
                                                'showDiff' => true,
                                                'mobileLayout' => true /* Pass this to your component */
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Final Tournament -->
                            @if($tournamentPair['final'])
                                <div class="bg-secondary-100 p-6 rounded-lg shadow-lg mt-6"> <!-- Added margin-top -->
                                    <h2 class="text-white text-2xl font-bold mb-4">
                                        {{ $tournamentPair['final']->title }} - {{ __('Final Results') }}
                                    </h2>
                                    @include('components.leaderboard-table', [
                                        'participants' => $tournamentPair['final']->leaderboards
                                    ])
                                </div>
                            @endif
                        </div>

                        <!-- Divider between tournaments (except last one) -->
                        @if(!$loop->last)
                            <div class="border-t border-gray-700 my-8"></div>
                        @endif
                    @endforeach
                </div>
            @else
                <!-- No tournaments message -->
                <div class="bg-secondary-100 p-6 rounded-lg shadow-lg text-center">
                    <p class="text-white text-xl">{{ __('No tournaments found for') }} {{ $selectedYear }}</p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>

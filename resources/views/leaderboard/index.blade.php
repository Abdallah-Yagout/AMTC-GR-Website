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
        @if(count($tournaments) > 0)
            <!-- Group tournaments by their main tournament ID -->
            @php
                $groupedTournaments = [];
                foreach ($tournaments as $tournament) {
                    if ($tournament['is_final']) {
                        $groupedTournaments[$tournament['tournament_id']]['final'] = $tournament;
                    } else {
                        $groupedTournaments[$tournament['id']]['main'] = $tournament;
                    }
                }
            @endphp

                <!-- Display grouped tournaments -->
            <div class="space-y-12">
                @foreach($groupedTournaments as $tournamentGroup)
                    <!-- Main Tournament -->
                    @if(isset($tournamentGroup['main']))
                        @php $mainTournament = $tournamentGroup['main']; @endphp
                        <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                            <h3 class="text-white text-2xl font-bold mb-4">
                                {{ $mainTournament['title'] }}
                            </h3>

                            <!-- Location Tabs -->
                            <div x-data="{ tab: '{{ array_key_first($mainTournament['locationLeaderboards']->toArray()) }}' }">
                                <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide">
                                    @foreach($mainTournament['locationLeaderboards'] as $location => $leaderboard)
                                        <button
                                            @click="tab = '{{ $location }}'"
                                            :class="tab === '{{ $location }}'
                                                ? 'border-b-4 border-primary text-red-600 font-semibold'
                                                : 'text-white border-transparent'"
                                            class="flex-shrink-0 py-2 px-4 transition whitespace-nowrap">
                                            {{ ucfirst($location) }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Leaderboard Per Location -->
                                @foreach($mainTournament['locationLeaderboards'] as $location => $leaderboard)
                                    <div x-show="tab === '{{ $location }}'" class="space-y-2 md:space-y-4">
                                        <h4 class="text-white text-xl font-bold mb-2">
                                            {{ ucfirst($location) }} Results
                                        </h4>

                                        @include('components.leaderboard-table', [
                                            'participants' => $leaderboard
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Final Tournament (if exists) -->
                        @if(isset($tournamentGroup['final']))
                            @php $finalTournament = $tournamentGroup['final']; @endphp
                            <div class="bg-secondary-100 p-6 rounded-lg shadow-lg mt-6">
                                <h3 class="text-white text-2xl font-bold mb-4">
                                    {{ $finalTournament['title'] }} - Final Results
                                </h3>

                                <!-- Location Tabs -->
                                <div x-data="{ tab: '{{ array_key_first($finalTournament['locationLeaderboards']->toArray()) }}' }">
                                    <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide">
                                        @foreach($finalTournament['locationLeaderboards'] as $location => $leaderboard)
                                            <button
                                                @click="tab = '{{ $location }}'"
                                                :class="tab === '{{ $location }}'
                                                    ? 'border-b-4 border-primary text-red-600 font-semibold'
                                                    : 'text-white border-transparent'"
                                                class="flex-shrink-0 py-2 px-4 transition whitespace-nowrap">
                                                {{ ucfirst($location) }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Leaderboard Per Location -->
                                    @foreach($finalTournament['locationLeaderboards'] as $location => $leaderboard)
                                        <div x-show="tab === '{{ $location }}'" class="space-y-2 md:space-y-4">
                                            <h4 class="text-white text-xl font-bold mb-2">
                                                {{ ucfirst($location) }} Final Results
                                            </h4>

                                            @include('components.leaderboard-table', [
                                                'participants' => $leaderboard
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <!-- Season Leaderboard -->
            <div class="bg-secondary-100 p-6 rounded-lg shadow-lg mt-12">
                <h2 class="text-white text-2xl font-bold mb-6">{{ __('Season Leaderboard') }}</h2>

                @include('components.leaderboard-table', [
                    'participants' => $seasonLeaderboard,
                    'showAll' => true
                ])
            </div>
        @else
            <!-- No tournaments message -->
            <div class="bg-secondary-100 p-6 rounded-lg shadow-lg text-center">
                <p class="text-white text-xl">{{ __('No tournaments found for') }} {{ $selectedYear }}</p>
            </div>
        @endif
    </section>
</x-app-layout>

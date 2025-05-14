<x-app-layout>
    <section class="dark:bg-primary-200 p-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">{{ __('Who\'s in the lead?') }}</h1>
        <p class="text-lg">{{ __('See who\'s ahead, who\'s catching up, and who\'s next to take the podium') }}</p>
    </section>

    <section class="container mx-auto px-4 py-8">
        <!-- Two-column layout for leaderboards -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Final Tournament Leaderboard Column -->
            <div class="lg:w-1/2">

                @if(isset($finalLeaderboard) && $finalLeaderboard->count())
                    <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                        <h1 class="text-white text-3xl font-bold mb-4">{{ $finalTournament->title }} - {{__('Final Results')}}</h1>

                        <!-- Header - Hidden on small screens -->
                        <div class="hidden sm:grid grid-cols-12 gap-4 px-2 py-2 border-b border-gray-700">
                            <div class="col-span-1 text-white font-bold text-center">#</div>
                            <div class="col-span-6 text-white font-bold">{{__('Driver')}}</div>
                            <div class="col-span-2 text-white font-bold text-center">{{__('Time')}}</div>
                            <div class="col-span-3 text-white font-bold text-center">{{__('Points')}}</div>
                        </div>

                        @foreach($finalLeaderboard as $participant)
                            @php
                                $minutes = floor($participant->time_taken / 60);
                                $seconds = $participant->time_taken % 60;
                            @endphp

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center border-b border-gray-700 p-3">
                                <!-- Position -->
                                <div class="sm:col-span-1 text-white text-xl font-bold sm:text-center">
                                    <span class="sm:hidden font-semibold text-base mr-2">#</span>
                                    {{ $participant->position }}
                                </div>

                                <!-- Driver -->
                                <div class="sm:col-span-6 flex items-center gap-3">
                                    <div class="text-white font-semibold text-lg truncate">
                                        <span class="sm:hidden font-semibold text-base mr-2">{{__('Driver')}}:</span>
                                    </div>
                                    <img src="{{ $participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->name) }}"
                                         alt="{{ $participant->user->name }}"
                                         class="w-10 h-10 rounded-full object-cover border border-white">
                                    <span class="text-white">{{ $participant->user->name }}</span>
                                </div>

                                <!-- Time -->
                                <div class="sm:col-span-2 text-green-400 text-lg font-bold sm:text-center">
                                    <span class="sm:hidden font-semibold text-base text-white mr-2">{{__('Time')}}:</span>
                                    {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                                </div>

                                <!-- Points -->
                                <div class="sm:col-span-3 text-white text-lg font-bold sm:text-center">
                                    <span class="sm:hidden font-semibold text-base mr-2">{{__('Points')}}:</span>
                                    {{ $participant->points }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Regular Leaderboard Column -->
            <div class="lg:w-1/2">
                <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                    <h1 class="text-white text-3xl font-bold mb-4">{{ __('Season Leaderboard') }}</h1>

                    <!-- Location Tabs -->
                    <div x-data="{ tab: '{{ array_key_first($season_leaderboard->toArray()) }}' }">
                        <div class="flex space-x-4 mb-6">
                            @foreach($season_leaderboard as $location => $participants)
                                <button
                                    @click="tab = '{{ $location }}'"
                                    :class="tab === '{{ $location }}'
                                        ? 'border-b-4 border-primary cursor-pointer text-red-600 font-semibold'
                                        : 'text-white cursor-pointer border-transparent'"
                                    class="py-2 px-4 transition">
                                    {{ ucfirst($location) }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Leaderboard Per Location -->
                        @foreach($season_leaderboard as $location => $participants)
                            <div x-show="tab === '{{ $location }}'" class="space-y-4">
                                <h2 class="text-white text-2xl font-bold mb-4">{{ ucfirst($location) }} Leaderboard</h2>

                                <!-- Header - Hidden on small screens -->
                                <div class="hidden sm:grid grid-cols-12 gap-4 px-2 py-2 border-b border-gray-700">
                                    <div class="col-span-1 text-white font-bold text-center">#</div>
                                    <div class="col-span-6 text-white font-bold">{{__('Driver')}}</div>
                                    <div class="col-span-2 text-white font-bold text-center">{{__('Time')}}</div>
                                    <div class="col-span-3 text-white font-bold text-center">{{__('+Diff')}}</div>
                                </div>

                                @foreach($participants as $index => $participant)
                                    @php
                                        $minutes = floor($participant->avg_time / 60);
                                        $seconds = $participant->avg_time % 60;
                                        $diff = $participant->avg_time - $participants[0]->avg_time;
                                    @endphp
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center border-b border-gray-700 p-3">
                                        <!-- Position -->
                                        <div class="sm:col-span-1 text-white text-xl font-bold sm:text-center">
                                            <span class="sm:hidden font-semibold text-base mr-2">#</span>
                                            {{ $participant->position }}
                                        </div>

                                        <!-- Driver -->
                                        <div class="sm:col-span-6 flex items-center gap-3">
                                            <div class="text-white font-semibold text-lg truncate">
                                                <span class="sm:hidden font-semibold text-base mr-2">{{__('Driver')}}:</span>
                                            </div>
                                            <img src="{{ $participant->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->name) }}"
                                                 alt="{{ $participant->name }}"
                                                 class="w-10 h-10 rounded-full object-cover border border-white">
                                                <span class="text-white">{{ $participant->name }}</span>
                                        </div>

                                        <!-- Time -->
                                        <div class="sm:col-span-2 text-green-400 text-lg font-bold sm:text-center">
                                            <span class="sm:hidden font-semibold text-base text-white mr-2">{{__('Time')}}:</span>
                                            {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                                        </div>

                                        <!-- Diff -->
                                        <div class="sm:col-span-3 text-sm font-bold sm:text-center {{ $diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                                            <span class="sm:hidden font-semibold text-base text-white mr-2">{{__('+Diff')}}:</span>
                                            {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 3) }}s
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>

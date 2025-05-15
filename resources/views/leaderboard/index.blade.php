<x-app-layout>
    <section class="dark:bg-primary-200 p-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">{{ __('Who\'s in the lead?') }}</h1>
        <p class="text-lg">{{ __('See who\'s ahead, who\'s catching up, and who\'s next to take the podium') }}</p>
    </section>

    <section class="container mx-auto px-4 py-8">
        @if(isset($finalTournament))

            <!-- Two-column layout when final leaderboard exists -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Final Tournament Leaderboard Column -->
                <div class="lg:w-1/2">
                    <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                        <h1 class="text-white text-3xl font-bold mb-4">{{ $finalTournament->title }} - {{__('Final Results')}}</h1>

                        <!-- Header - Hidden on small screens -->
                        <div class="hidden sm:grid grid-cols-12 gap-4 px-2 py-2 border-b border-gray-700">
                            <div class="col-span-1 text-white font-bold text-center">#</div>
                            <div class="col-span-6 text-white font-bold">{{__('Driver')}}</div>
                            <div class="col-span-2 text-white font-bold text-center">{{__('Time')}}</div>
                        </div>

                        @foreach($finalLeaderboard as $participant)
                            @php
                                $minutes = floor($participant->time_taken / 60);
                                $seconds = $participant->time_taken % 60;
                            @endphp

                            <div class="grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
                                <!-- Position -->
                                <div class="col-span-2 md:col-span-1 text-white text-lg md:text-xl font-bold text-center">
                                    <span class="sm:hidden text-sm mr-1">#</span>
                                    {{ $participant->position }}
                                </div>

                                <!-- Driver -->
                                <div class="col-span-7 md:col-span-6 flex items-center gap-2 md:gap-3">
                                    <img src="{{ $participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) }}"
                                         alt="{{ $participant->user->name }}"
                                         class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-white">
                                    <span class="text-white text-sm md:text-base truncate">
                                        {{ $participant->user->name }}
                                    </span>
                                </div>

                                <!-- Time -->
                                <div class="col-span-3 md:col-span-2 text-green-400 text-sm md:text-lg font-bold text-right md:text-center">
                                    <span class="sm:hidden text-xs text-white mr-1">Time:</span>
                                    {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Season Leaderboard Column -->
                <div class="lg:w-1/2">
                    <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                        <h1 class="text-white text-3xl font-bold mb-4">{{ __('Season Leaderboard') }}</h1>

                        <!-- Location Tabs -->
                        <div x-data="{ tab: '{{ array_key_first($season_leaderboard->toArray()) }}' }">
                            <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide">
                                @foreach($season_leaderboard as $location => $participants)
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
                            @foreach($season_leaderboard as $location => $participants)
                                <div x-show="tab === '{{ $location }}'" class="space-y-2 md:space-y-4">
                                    <h2 class="text-white text-xl md:text-2xl font-bold mb-2 md:mb-4">
                                        {{ ucfirst($location) }} Leaderboard
                                    </h2>

                                    <!-- Header -->
                                    <div class="hidden sm:grid grid-cols-12 gap-2 md:gap-4 px-2 py-2 border-b border-gray-700">
                                        <div class="col-span-1 text-white font-bold text-center">#</div>
                                        <div class="col-span-6 text-white font-bold">{{__('Driver')}}</div>
                                        <div class="col-span-2 text-white font-bold text-center">{{__('Time')}}</div>
                                        <div class="col-span-3 text-white font-bold text-center">{{__('+Diff')}}</div>
                                    </div>

                                    @foreach($participants as $index => $participant)
                                        @php
                                            $minutes = floor($participant->time_taken / 60);
                                            $seconds = $participant->time_taken % 60;
                                            $diff = $participant->time_taken - $participants[0]->time_taken;
                                        @endphp

                                        <div class="grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
                                            <!-- Position -->
                                            <div class="col-span-2 md:col-span-1 text-white text-lg font-bold text-center">
                                                <span class="sm:hidden text-sm mr-1">#</span>
                                                {{ $participant->position }}
                                            </div>

                                            <!-- Driver -->
                                            <div class="col-span-6 md:col-span-6 flex items-center gap-2 md:gap-3">
                                                <img src="{{ $participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) }}"
                                                     alt="{{ $participant->user->name }}"
                                                     class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-white">
                                                <span class="text-white text-sm md:text-base truncate">
                                                    {{ $participant->user->name }}
                                                </span>
                                            </div>

                                            <!-- Time -->
                                            <div class="col-span-2 md:col-span-2 text-green-400 text-sm md:text-base font-bold text-right md:text-center">
                                                <span class="sm:hidden text-xs text-white mr-1">Time:</span>
                                                {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                                            </div>

                                            <!-- Diff -->
                                            <div class="col-span-2 md:col-span-3 text-xs md:text-sm font-bold text-right md:text-center {{ $diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                                                <span class="sm:hidden text-xs text-white mr-1">Diff:</span>
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
        @else
            <!-- Centered single column when no final leaderboard -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                    <h1 class="text-white text-3xl font-bold mb-4">{{ __('Season Leaderboard') }}</h1>

                    <!-- Location Tabs -->
                    <div x-data="{ tab: '{{ array_key_first($season_leaderboard->toArray()) }}' }">
                        <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide">
                            @foreach($season_leaderboard as $location => $participants)
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
                        @foreach($season_leaderboard as $location => $participants)
                            <div x-show="tab === '{{ $location }}'" class="space-y-2 md:space-y-4">
                                <h2 class="text-white text-xl md:text-2xl font-bold mb-2 md:mb-4">
                                    {{ ucfirst($location) }} Leaderboard
                                </h2>

                                <!-- Header -->
                                <div class="hidden sm:grid grid-cols-12 gap-2 md:gap-4 px-2 py-2 border-b border-gray-700">
                                    <div class="col-span-1 text-white font-bold text-center">#</div>
                                    <div class="col-span-6 text-white font-bold">{{__('Driver')}}</div>
                                    <div class="col-span-2 text-white font-bold text-center">{{__('Time')}}</div>
                                    <div class="col-span-3 text-white font-bold text-center">{{__('+Diff')}}</div>
                                </div>

                                @foreach($participants as $index => $participant)
                                    @php
                                        $minutes = floor($participant->time_taken / 60);
                                        $seconds = $participant->time_taken % 60;
                                        $diff = $participant->time_taken - $participants[0]->time_taken;
                                    @endphp

                                    <div class="grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
                                        <!-- Position -->
                                        <div class="col-span-2 md:col-span-1 text-white text-lg font-bold text-center">
                                            <span class="sm:hidden text-sm mr-1">#</span>
                                            {{ $participant->position }}
                                        </div>

                                        <!-- Driver -->
                                        <div class="col-span-6 md:col-span-6 flex items-center gap-2 md:gap-3">
                                            <img src="{{ $participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) }}"
                                                 alt="{{ $participant->user->name }}"
                                                 class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-white">
                                            <span class="text-white text-sm md:text-base truncate">
                                                {{ $participant->user->name }}
                                            </span>
                                        </div>

                                        <!-- Time -->
                                        <div class="col-span-2 md:col-span-2 text-green-400 text-sm md:text-base font-bold text-right md:text-center">
                                            <span class="sm:hidden text-xs text-white mr-1">Time:</span>
                                            {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                                        </div>

                                        <!-- Diff -->
                                        <div class="col-span-2 md:col-span-3 text-xs md:text-sm font-bold text-right md:text-center {{ $diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                                            <span class="sm:hidden text-xs text-white mr-1">Diff:</span>
                                            {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 3) }}s
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </section>
</x-app-layout>

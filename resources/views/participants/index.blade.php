<x-app-layout>
    <section class="dark:bg-primary-200 p-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">{{__('Who\'s in the lead?')}}</h1>
        <p class="text-lg">{{__('See who’s ahead, who’s catching up, and who’s next to take the podium')}}</p>
    </section>
    <section class="bg-secondary-100 p-10">
        <h1 class="text-white text-4xl font-bold mb-2">{{__('Results & Leaderboard')}}</h1>
        <p class="text-secondary-300 text-xl mb-10">{{ $tournament->title}}</p>

        <section class="flex w-full gap-10">
            <!-- Real-Time Results Section (unchanged) -->
            <section class="bg-black w-full rounded-lg px-6 py-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-white text-3xl font-bold">{{__('Real-Time Results')}}</h2>
                </div>
                <ul class="space-y-4">
                    @foreach($participants as $participant)
                        <li class="flex items-center justify-between bg-secondary-100 p-4 rounded-xl shadow-lg">
                            {{-- Left: Rank, Image, Name, Location --}}
                            <div class="flex items-center gap-4">
                                <div class="text-white text-2xl font-bold w-6">{{ $participant->position }}</div>
                                <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($participant->user->name) }}"
                                     alt="{{ $participant->user->name }}"
                                     class="w-12 h-12 rounded-full object-cover border border-white">
                                <div>
                                    <div class="text-white text-lg font-semibold">{{ $participant->user->name }}</div>
                                    <div class="text-gray-400 text-sm">{{ __($participant->location) }}</div>
                                </div>
                            </div>

                            {{-- Right: Time --}}
                            @php
                                $minutes = floor($participant->time_taken / 60);
                                $seconds = $participant->time_taken % 60;
                            @endphp
                            <div class="text-right">
                                <div class="text-green-400 text-2xl font-bold">
                                    {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                                </div>
                                <div class="text-sm {{ $participant->time_diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                                    {{ $participant->time_diff > 0 ? '+' : '' }}{{ number_format($participant->time_diff, 3) }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>

            <!-- Season Leaderboard Section -->
            <section class="bg-black w-full rounded-lg px-6 py-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-white text-3xl font-bold">{{__('Season Leaderboard')}}</h2>
                    <div class="relative">
                        <form method="GET" action="{{ route('participants.index') }}">
                            <select name="season" onchange="this.form.submit()"
                                    class="bg-secondary-100 text-white rounded-lg py-2 appearance-none">
                                @foreach($seasons as $season)
                                    <option value="{{ $season->season }}"
                                        {{ $selectedSeason == $season->season ? 'selected' : '' }}>
                                        Season {{ $season->season }}
                                    </option>
                                @endforeach
                            </select>

                        </form>
                    </div>
                </div>

                <!-- Column Headers -->
                <div class="grid grid-cols-12 gap-4 mb-4 px-4 py-2 border-b border-gray-700">
                    <div class="col-span-1 text-white font-bold text-center">Rank</div>
                    <div class="col-span-6 text-white font-bold">Driver</div>
                    <div class="col-span-2 text-white font-bold text-center">Wins</div>
                    <div class="col-span-3 text-white font-bold text-center">Points</div>
                </div>

                <!-- Participants List -->
                <ul class="space-y-4">
                    @foreach($season_leaderboard as $index => $participant)

                        <li class="grid grid-cols-12 gap-4 items-center border-b-1 border-[#454545] p-4 shadow-lg">
                            <!-- Rank Column -->
                            <div class="col-span-1 text-white text-2xl font-bold text-center">
                                {{$participant->position }}
                            </div>

                            <!-- Driver Column (Name + Avatar) -->
                            <div class="col-span-6 flex items-center gap-4">
                                <img src="{{ $participant->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($participant->name) }}"
                                     alt="{{ $participant->name }}"
                                     class="w-12 h-12 rounded-full object-cover border border-white">
                                <div class="text-white text-lg font-semibold">{{ $participant->name }}</div>
                            </div>

                            <!-- Wins Column -->
                            <div class="col-span-2 flex items-center justify-center gap-2">
                                <span class="text-white text-lg font-bold">{{ $participant->wins }}</span>
                            </div>

                            <!-- Points Column -->
                            <div class="col-span-3 flex items-center justify-center gap-2">
                                <span class="text-white text-lg font-bold">{{ $participant->total_points }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        </section>
    </section>
</x-app-layout>

<x-app-layout>
    <section class="dark:bg-primary-200 p-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">{{__('Who\'s in the lead?')}}</h1>
        <p class="text-lg">{{__('See who’s ahead, who’s catching up, and who’s next to take the podium')}}</p>
    </section>
    <section class="bg-secondary-100 p-10 min-h-screen">
        <h1 class="text-white text-4xl font-bold mb-2">{{__('Results & Leaderboard')}}</h1>
        <p class="text-secondary-300 text-xl mb-10">{{ $tournament->title}}</p>

        <section class="bg-black rounded-lg px-6 py-4">
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
    </section>

</x-app-layout>

<x-app-layout>
    <section class="dark:bg-primary-200 px-10 py-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">Let the Race Begin!</h1>
        <p class="text-lg">Check out detailed event schedules and gear up for action in Mukalla, Aden, and Sana'a</p>
    </section>

    <section x-data="{ tab: '{{ array_key_first($tournamentsByLocation) }}' }" class="px-10 bg-black py-6">
        <!-- Tab Headers -->
        <div class="flex space-x-4 mb-6">
            @foreach($tournamentsByLocation as $location => $tournaments)
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

        <!-- Tab Contents -->
        @foreach($tournamentsByLocation as $location => $tournaments)
            <div x-show="tab === '{{ $location }}'" class="space-y-3 sm:space-y-4">
                @forelse($tournaments as $tournament)
                    <div class="p-3 sm:p-4 shadow-sm bg-white dark:bg-secondary-100 dark:text-white rounded-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg sm:text-xl font-semibold line-clamp-2">{{ $tournament->title }}</h3>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2 sm:line-clamp-3">
                                    {{ $tournament->description }}
                                </p>
                                <p class="text-xs sm:text-sm mt-2 text-gray-700 dark:text-gray-300">
                                    Date: {{ \Carbon\Carbon::parse($tournament->date)->format('F j, Y') }}
                                </p>
                            </div>
                            <div class="sm:w-auto w-full">

                                @if($tournament->location_participant_count >= $tournament->number_of_players)
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
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 p-4 text-center">
                        No tournaments available in {{ ucfirst($location) }}.
                    </p>
                @endforelse
            </div>
        @endforeach
    </section>
</x-app-layout>

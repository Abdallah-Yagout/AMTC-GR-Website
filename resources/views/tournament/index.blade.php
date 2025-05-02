<x-app-layout>
    <section class="dark:bg-primary-200 px-10 py-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2">Let the Race Begin!</h1>
        <p class="text-lg">Check out detailed event schedules and gear up for action in Mukalla, Aden, and Sana'a</p>
    </section>

    <section x-data="{ tab: '{{ array_key_first($tournamentsByLocation->toArray()) }}' }" class="px-10 bg-black py-6">
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
            <div x-show="tab === '{{ $location }}'" class="space-y-4">
                @forelse($tournaments as $tournament)
                    <div class="p-4 shadow-sm bg-white dark:bg-secondary-100 dark:text-white">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold">{{ $tournament->title }}</h3>
                            <a href="{{route('tournament.apply',['id'=>$tournament->id])}}" class="text-sm px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                {{__('Register')}}
                            </a>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $tournament->description }}</p>
                        <p class="text-sm mt-2">Date: {{ \Carbon\Carbon::parse($tournament->date)->format('F j, Y') }}</p>
                    </div>

                @empty
                    <p>No tournaments available in {{ ucfirst($location) }}.</p>
                @endforelse
            </div>
        @endforeach
    </section>
</x-app-layout>

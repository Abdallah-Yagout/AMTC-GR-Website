<div class="w-full space-y-8">
    {{-- Tournament Select Form --}}
    {{ $this->form }}

    @if ($totalParticipants === 0 && $locationStats->isEmpty() && $genderStats->isEmpty() && $skillLevelStats->isEmpty())
        <div class="w-full p-4 text-center text-gray-500 dark:text-gray-400">
            {{ __('No data available for the selected tournament.') }}
        </div>
    @else
        <!-- First Row: Total + Locations -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4 text-center">Participants by Location</h3>

            <div class="flex flex-wrap sm:flex-nowrap gap-4 overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                <!-- Total Participants Card -->
                <div class="flex-grow min-w-[180px] sm:min-w-[200px] sm:basis-1/6 p-4 shadow rounded-lg bg-white dark:bg-gray-800 text-center">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</div>
                    <div class="text-3xl font-bold mt-1">{{ number_format($totalParticipants) }}</div>
                </div>

                <!-- Location Cards -->
                @foreach ($locationStats->take(5) as $row)
                    <div class="flex-grow min-w-[180px] sm:min-w-[200px] sm:basis-1/6 p-4 shadow rounded-lg bg-white dark:bg-gray-800 text-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $row->city ?: 'Unknown' }}
                        </div>
                        <div class="text-2xl font-bold mt-1">
                            {{ number_format($row->total) }}
                        </div>
                    </div>
                @endforeach

                <!-- Show More Locations if > 5 -->
                @if ($locationStats->count() > 5)
                    <div class="flex-grow min-w-[180px] sm:min-w-[200px] sm:basis-1/6 p-4 shadow rounded-lg bg-white dark:bg-gray-800 text-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            +{{ $locationStats->count() - 5 }} more
                        </div>
                        <div class="text-2xl font-bold mt-1">
                            {{ number_format($locationStats->skip(5)->sum('total')) }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Second Row: Gender Stats -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4 text-center">Participants by Gender</h3>

            <div class="flex flex-wrap sm:flex-nowrap gap-4 overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                @forelse ($genderStats as $row)
                    <div class="flex-grow min-w-[180px] sm:min-w-[200px] sm:basis-1/6 p-4 shadow rounded-lg bg-white dark:bg-gray-800 text-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $row->gender ?: 'Unknown' }}
                        </div>
                        <div class="text-2xl font-bold mt-1">
                            {{ number_format($row->total) }}
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-4 text-gray-500">
                        No gender data available
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Third Row: Skill Level Stats -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4 text-center">Participants by Skill Level</h3>

            <div class="flex flex-wrap sm:flex-nowrap gap-4 overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                @forelse ($skillLevelStats as $row)
                    <div class="flex-grow min-w-[180px] sm:min-w-[200px] sm:basis-1/6 p-4 shadow rounded-lg bg-white dark:bg-gray-800 text-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $row->skill_level ?: 'Unknown' }}
                        </div>
                        <div class="text-2xl  font-bold mt-1">
                            {{ number_format($row->total) }}
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-4 text-gray-500">
                        No skill level data available
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>

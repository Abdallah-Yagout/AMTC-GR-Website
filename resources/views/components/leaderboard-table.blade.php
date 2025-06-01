<div class="w-full">
    <!-- Desktop Header (hidden on mobile) -->
    <div class="hidden sm:grid grid-cols-12 gap-2 md:gap-4 px-2 py-2 border-b border-gray-700">
        <div class="col-span-1 text-white font-bold text-center">#</div>
        <div class="col-span-6 text-white font-bold">{{__('Driver')}}</div>
        <div class="col-span-2 text-white font-bold text-center">{{__('Time')}}</div>
        @if(isset($showDiff) && $showDiff)
            <div class="col-span-3 text-white font-bold text-center">{{__('+Diff')}}</div>
        @endif
        @if(isset($showAll) && $showAll)
            <div class="col-span-3 text-white font-bold text-center">{{__('Tournament')}}</div>
        @endif
    </div>

    @foreach($participants as $participant)
        @php
            $timeTaken = $participant->time_taken ?? null;
            $position = $participant->position ?? null;
            $minutes = $timeTaken !== null ? floor($timeTaken / 60) : 0;
            $seconds = $timeTaken !== null ? $timeTaken % 60 : 0;
            $diff = (isset($showDiff) && $showDiff && $timeTaken !== null && isset($participants[0]->time_taken))
                ? $timeTaken - $participants[0]->time_taken
                : 0;
        @endphp

            <!-- Mobile Layout (flex) -->
        <div class="sm:hidden flex flex-col border-b border-gray-700 p-3 space-y-2">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-white font-bold">#{{ $position ?? 'N/A' }}</span>
                    <img src="{{ $participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) }}"
                         alt="{{ $participant->user->name }}"
                         class="w-8 h-8 rounded-full object-cover border border-white">
                    <span class="text-white text-sm truncate">
                        {{ $participant->user->name }}
                    </span>
                </div>
            </div>

            <div class="flex justify-between">
                <div class="text-white text-xs">Time:</div>
                <div class="text-green-400 text-sm font-bold">
                    @if($timeTaken !== null)
                        {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                    @else
                        N/A
                    @endif
                </div>
            </div>

            @if(isset($showDiff) && $showDiff)
                <div class="flex justify-between">
                    <div class="text-white text-xs">Diff:</div>
                    <div class="text-xs font-bold {{ $diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                        {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 3) }}s
                    </div>
                </div>
            @endif

            @if(isset($showAll) && $showAll)
                <div class="flex justify-between">
                    <div class="text-white text-xs">Tournament:</div>
                    <div class="text-gray-300 text-xs truncate">
                        {{ $participant->tournament->title }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Desktop Layout (grid) -->
        <div class="hidden sm:grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
            <!-- Position -->
            <div class="col-span-1 text-white text-lg font-bold text-center">
                {{ $position ?? 'N/A' }}
            </div>

            <!-- Driver -->
            <div class="col-span-6 flex items-center gap-3">
                <img src="{{ $participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) }}"
                     alt="{{ $participant->user->name }}"
                     class="w-10 h-10 rounded-full object-cover border border-white">
                <span class="text-white text-base truncate">
                    {{ $participant->user->name }}
                </span>
            </div>

            <!-- Time -->
            <div class="col-span-2 text-green-400 text-base font-bold text-center">
                @if($timeTaken !== null)
                    {{ sprintf('%d:%06.3f', $minutes, $seconds) }}
                @else
                    N/A
                @endif
            </div>

            @if(isset($showDiff) && $showDiff)
                <!-- Diff -->
                <div class="col-span-3 text-sm font-bold text-center {{ $diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                    {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 3) }}s
                </div>
            @endif

            @if(isset($showAll) && $showAll)
                <!-- Tournament Name -->
                <div class="col-span-3 text-sm text-gray-300 text-center truncate">
                    {{ $participant->tournament->title }}
                </div>
            @endif
        </div>
    @endforeach
</div>

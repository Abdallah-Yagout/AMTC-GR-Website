<!-- Header -->
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
        $minutes = floor($participant->time_taken / 60);
        $seconds = $participant->time_taken % 60;
        $diff = isset($showDiff) && $showDiff ? $participant->time_taken - $participants[0]->time_taken : 0;
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

        @if(isset($showDiff) && $showDiff)
            <!-- Diff -->
            <div class="col-span-2 md:col-span-3 text-xs md:text-sm font-bold text-right md:text-center {{ $diff > 0 ? 'text-red-400' : 'text-green-400' }}">
                <span class="sm:hidden text-xs text-white mr-1">Diff:</span>
                {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 3) }}s
            </div>
        @endif

        @if(isset($showAll) && $showAll)
            <!-- Tournament Name -->
            <div class="col-span-2 md:col-span-3 text-xs md:text-sm text-gray-300 text-right md:text-center truncate">
                {{ $participant->tournament->title }}
            </div>
        @endif
    </div>
@endforeach

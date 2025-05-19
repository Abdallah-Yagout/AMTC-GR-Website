@props([
    'participants',
    'showPosition' => true,
    'showPoints' => true,
    'compact' => false,
])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-700">
        <thead>
        <tr>
            @if($showPosition)
                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Position</th>
            @endif
            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Name</th>
            @if($showPoints)
                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Points</th>
            @endif
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
        @forelse($participants as $index => $participant)
            <tr class="{{ $index % 2 === 0 ? 'bg-secondary-100' : 'bg-secondary-150' }}">
                @if($showPosition)
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-white">
                        {{ $participant->position ?? $index + 1 }}
                    </td>
                @endif

                <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                    <div class="flex items-center gap-3">
                        <img src="{{ $participant->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name ?? 'Unknown') }}"
                             alt="{{ $participant->user->name ?? 'Unknown' }}"
                             class="w-8 h-8 rounded-full object-cover border border-gray-600">
                        <span>{{ $participant->user->name ?? $participant['user']['name'] ?? 'Unknown User' }}</span>
                    </div>
                </td>

                @if($showPoints)
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-white font-medium">
                        {{ $participant->points ?? $participant['total_points'] ?? 0 }}
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $showPosition ? ($showPoints ? 3 : 2) : ($showPoints ? 2 : 1) }}"
                    class="px-4 py-3 text-center text-gray-500">
                    {{ __('No participants found.') }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

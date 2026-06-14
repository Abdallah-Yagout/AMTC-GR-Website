@props(['leaders'])

<div class="games-table-scroll">
    <table class="games-table">
        <thead>
            <tr>
                <th>{{ __('Rank') }}</th>
                <th>{{ __('Player') }}</th>
                <th>{{ __('Points') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaders as $index => $entry)
                @php
                    $rank = $index + 1;
                    $podiumClass = match ($rank) {
                        1 => 'games-row--gold',
                        2 => 'games-row--silver',
                        3 => 'games-row--bronze',
                        default => '',
                    };
                @endphp
                <tr class="{{ $podiumClass }}">
                    <td>
                        <span class="games-rank-cell">#{{ $rank }}</span>
                    </td>
                    <td>{{ optional($entry->user)->name ?? __('Unknown Player') }}</td>
                    <td><span class="games-points-cell">{{ number_format((int) $entry->points) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="games-table-empty">{{ __('No games points yet. Be the first to claim daily points!') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

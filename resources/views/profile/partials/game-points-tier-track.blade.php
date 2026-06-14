@php
    $track = $gameTierTrack ?? \App\Support\GamePointsTierProgress::build((int) ($gamePoints ?? 0));
    $tiers = $track['tiers'];
    $tierCount = count($tiers);
    $lastSegIndex = max(0, $tierCount - 2);
    $currentIndex0 = $track['current_index'] - 1;
    $progressPct = (int) round(100 * (float) $track['progress_to_next']);

    $segmentClass = function (int $k) use ($currentIndex0, $lastSegIndex, $tierCount): string {
        $base = 'profile-tier-hex-seg profile-tier-hex-seg--side';
        if ($k < 0 || $k > $lastSegIndex) {
            return $base.' is-spacer';
        }
        if ($k < $currentIndex0) {
            return $base.' is-done';
        }
        if ($k === $currentIndex0 && $currentIndex0 < $tierCount - 1) {
            return $base.' is-partial';
        }

        return $base.' is-todo';
    };
@endphp

<section
    class="profile-tier-track"
    style="--tier-progress: {{ $track['progress_to_next'] }}; --tier-progress-pct: {{ $progressPct }}%; --profile-tier-count: {{ $tierCount }};"
    aria-labelledby="profile-tier-track-title"
>
    <div class="profile-tier-track-head">
        <div>
            <h2 id="profile-tier-track-title" class="profile-tier-track-title">{{ __('Games reward path') }}</h2>
            <p class="profile-tier-track-sub">
                {{ __('You have :pts Games points.', ['pts' => number_format($track['points'])]) }}
                @if (! is_null($track['next_min_points']) && $track['points_to_next'] > 0)
                    {{ __('Next tier at :pts pts (:remain to go).', [
                        'pts' => number_format($track['next_min_points']),
                        'remain' => number_format($track['points_to_next']),
                    ]) }}
                @elseif (is_null($track['next_min_points']))
                    {{ __('Maximum tier reached.') }}
                @endif
            </p>
        </div>
        <a href="{{ route('games.index') }}" class="profile-tier-track-link">{{ __('Earn points') }}</a>
    </div>

    <div class="profile-tier-track-scroll">
        <div class="profile-tier-columns" role="list">
            @foreach ($tiers as $tier)
                @php
                    $idx = $loop->index;
                    $leftSegClass = $segmentClass($idx - 1);
                    $rightSegClass = $segmentClass($idx < $tierCount - 1 ? $idx : -1);
                @endphp
                <div class="profile-tier-col" role="listitem">
                    <div class="profile-tier-col-track" aria-hidden="true">
                        <span @class([
                            $leftSegClass,
                            'profile-tier-hex-seg--inbound' => str_contains($leftSegClass, 'is-partial'),
                        ])></span>
                        <div
                            class="profile-tier-hex profile-tier-hex--{{ $tier['state'] }}"
                            aria-label="{{ __('Tier :n', ['n' => $tier['index']]) }}"
                            style="animation-delay: {{ ($tier['index'] - 1) * 45 }}ms"
                        >
                            <span class="profile-tier-hex-inner">{{ $tier['index'] }}</span>
                        </div>
                        <span @class([
                            $rightSegClass,
                            'profile-tier-hex-seg--outbound' => str_contains($rightSegClass, 'is-partial'),
                        ])></span>
                    </div>
                    <article
                        class="profile-tier-card profile-tier-card--{{ $tier['state'] }}"
                        style="--stagger: {{ $loop->index }};"
                    >
                        <div class="profile-tier-card-icon" aria-hidden="true">
                            <img
                                class="profile-tier-badge-img"
                                src="{{ tier_badge_image_url($tier['badge']) }}"
                                alt=""
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <h3 class="profile-tier-card-title">{{ __($tier['label']) }}</h3>
                        <p class="profile-tier-card-req">
                            @if ($tier['state'] === \App\Support\GamePointsTierProgress::STATE_LOCKED)
                                {{ __('Requires :pts pts', ['pts' => number_format($tier['min_points'])]) }}
                            @else
                                {{ __('From :pts pts', ['pts' => number_format($tier['min_points'])]) }}
                            @endif
                        </p>
                        @if ($tier['state'] === \App\Support\GamePointsTierProgress::STATE_CURRENT)
                            <div class="profile-tier-card-banner">{{ __('Current reward') }}</div>
                        @endif
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>

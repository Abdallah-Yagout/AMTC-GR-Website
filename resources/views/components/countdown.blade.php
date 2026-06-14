@props(['title', 'date' => null])
<div id="countdown" class="next-race-wrap" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="next-race-panel">
        <div class="next-race-headline-strip">
            <span>{{ __('Next Up') }}</span>
        </div>

        <h2 class="next-race-title">{{ __('Next Race Event') }}</h2>
        <p class="next-race-subtitle">{{ $title ?? __('No upcoming events') }}</p>

        <div class="next-race-grid">
            @foreach(['days', 'hours', 'minutes', 'seconds'] as $unit)
                <div class="next-race-tile">
                    <div class="next-race-tile-number-wrap">
                        <div id="{{ $unit }}-flip" class="next-race-tile-number transition-all duration-300 ease-in-out transform scale-100">
                            00
                        </div>
                    </div>
                    <div class="next-race-tile-label">
                        @if(app()->getLocale() === 'ar')
                            {{ __(ucfirst($unit)) }}
                        @else
                            {{ strtoupper($unit) }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

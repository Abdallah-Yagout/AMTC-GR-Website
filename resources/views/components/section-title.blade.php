@props(['variant' => 'default', 'complete' => null])

<div class="md:col-span-1 flex justify-between">
    <div class="min-w-0 flex-1 px-4 sm:px-0">
        @if ($variant === 'gaming')
            <div class="profile-section-title-row flex flex-wrap items-center gap-x-2 gap-y-1">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">{{ $title }}</h3>
                @if ($complete === true)
                    <span class="profile-section-badge profile-section-badge--complete">{{ __('Complete') }}</span>
                @elseif ($complete === false)
                    <span class="profile-section-badge profile-section-badge--incomplete">{{ __('Incomplete') }}</span>
                @endif
            </div>
            <p class="mt-2 text-sm leading-relaxed text-zinc-400">
                {{ $description }}
            </p>
        @else
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $description }}
            </p>
        @endif
    </div>

    <div class="shrink-0 px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>

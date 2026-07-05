@props(['variant' => 'default', 'incomplete' => false])

<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        @if ($variant === 'gaming')
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">{{ $title }}</h3>
                @if ($incomplete)
                    <span class="profile-section-badge-incomplete">{{ __('Incomplete') }}</span>
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

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>

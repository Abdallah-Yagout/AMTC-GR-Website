@props(['submit', 'variant' => 'default', 'incomplete' => false])

@php
    $isGaming = $variant === 'gaming';
    $panelTop = $isGaming
        ? 'px-4 py-5 sm:p-6 bg-zinc-900/95 border border-zinc-700 border-l-4 border-l-primary shadow-lg shadow-black/20 ' . (isset($actions) ? 'rounded-t-lg' : 'rounded-lg')
        : 'px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow-sm ' . (isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md');
    $panelFooter = $isGaming
        ? 'flex items-center justify-end px-4 py-3 bg-zinc-950 border-t border-zinc-700 text-end sm:px-6 sm:rounded-b-lg'
        : 'flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow-sm sm:rounded-bl-md sm:rounded-br-md';

    if ($isGaming && $incomplete) {
        $panelTop = str_replace('border-l-primary', 'border-l-amber-400', $panelTop);
    }
@endphp

<div @class([
    'md:grid md:grid-cols-3 md:gap-6',
    'profile-form-section--incomplete' => $isGaming && $incomplete,
])>
    <x-section-title :variant="$variant" :incomplete="$incomplete">
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}">
            <div class="{{ $panelTop }}">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="{{ $panelFooter }}">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>

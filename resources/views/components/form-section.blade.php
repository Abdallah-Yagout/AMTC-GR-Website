@props(['submit', 'variant' => 'default', 'complete' => null])

@php
    $isGaming = $variant === 'gaming';
    $hasCompletionStatus = $isGaming && ! is_null($complete);
    $panelShape = isset($actions) ? 'rounded-t-lg' : 'rounded-lg';
    $footerShape = 'sm:rounded-b-lg';

    $panelTop = $isGaming
        ? "profile-form-section-panel px-4 py-5 sm:p-6 bg-zinc-900/95 border border-zinc-700 border-l-4 border-l-primary shadow-lg shadow-black/20 {$panelShape}"
        : 'px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow-sm ' . (isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md');
    $panelFooter = $isGaming
        ? "profile-form-section-panel-footer flex items-center justify-end px-4 py-3 bg-zinc-950 border-t border-zinc-700 text-end sm:px-6 {$footerShape}"
        : 'flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow-sm sm:rounded-bl-md sm:rounded-br-md';
@endphp

<div @class([
    'profile-form-section md:grid md:grid-cols-3 md:gap-6',
    'profile-form-section--incomplete' => $hasCompletionStatus && $complete === false,
    'profile-form-section--complete' => $hasCompletionStatus && $complete === true,
])>
    <x-section-title :variant="$variant" :complete="$complete">
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

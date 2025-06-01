@props(['disabled' => false])

<select
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => '
            border-gray-700
            bg-gray-800
            text-gray-300
            focus:border-gray-600
            focus:ring-gray-600
            rounded-md
            shadow-xs
            appearance-none
        '
    ]) }}>
    {{ $slot }}
</select>

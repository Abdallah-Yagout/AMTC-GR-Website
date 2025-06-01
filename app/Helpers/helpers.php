<?php
if (!function_exists('avatar_url')) {
    function avatar_url(string $name): string
    {
        $initials = \Illuminate\Support\Str::of($name)
            ->trim()
            ->explode(' ')
            ->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))
            ->take(2)
            ->join('');

        $color = substr(md5($name), 0, 6);

        return "https://ui-avatars.com/api/?name={$initials}&background={$color}&color=fff&size=128";
    }
}

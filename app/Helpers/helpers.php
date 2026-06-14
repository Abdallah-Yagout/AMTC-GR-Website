<?php

if (! function_exists('tier_badge_image_url')) {
    function tier_badge_image_url(string $badgeKey): string
    {
        $map = config('game_reward_tiers.badge_image', []);
        $file = $map[$badgeKey] ?? $map['bronze'] ?? 'starter-lane.png';

        return asset('images/tier-badges/'.$file);
    }
}

if (! function_exists('avatar_url')) {
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

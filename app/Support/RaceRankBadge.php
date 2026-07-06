<?php

namespace App\Support;

final class RaceRankBadge
{
    public static function cssClass(?int $position): string
    {
        return match ($position) {
            1 => 'is-gold',
            2 => 'is-silver',
            3 => 'is-bronze',
            default => 'is-default',
        };
    }
}

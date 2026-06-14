<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Games points reward tiers (profile progression)
    |--------------------------------------------------------------------------
    |
    | Exactly 8 tiers, sorted by min_points ascending. Tier 1 should be 0.
    | Labels are translation keys (profile.tier.*) — wrap with __() in views.
    |
    */

    'tiers' => [
        ['min_points' => 0, 'label' => 'Starter Lane', 'badge' => 'bronze'],
        ['min_points' => 75, 'label' => 'Bronze Grid', 'badge' => 'bronze_plus'],
        ['min_points' => 200, 'label' => 'Silver Apex', 'badge' => 'silver'],
        ['min_points' => 500, 'label' => 'Gold Rush', 'badge' => 'gold'],
        ['min_points' => 1200, 'label' => 'Elite Cup', 'badge' => 'trophy_green'],
        ['min_points' => 3000, 'label' => 'Champion Run', 'badge' => 'trophy_gold'],
        ['min_points' => 6000, 'label' => 'Master Class', 'badge' => 'trophy_crystal'],
        ['min_points' => 10000, 'label' => 'Peak of Glory', 'badge' => 'trophy_peak'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Badge keys (profile card styles)
    |--------------------------------------------------------------------------
    */
    'badge_options' => [
        'bronze' => 'Starter Lane (artwork)',
        'bronze_plus' => 'Bronze Grid (artwork)',
        'silver' => 'Silver Apex (artwork)',
        'gold' => 'Gold Rush (artwork)',
        'trophy_green' => 'Elite Cup (artwork)',
        'trophy_gold' => 'Champion Run (artwork)',
        'trophy_crystal' => 'Master Class (artwork)',
        'trophy_peak' => 'Peak of Glory (artwork)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Badge PNG filenames (public/images/tier-badges/)
    |--------------------------------------------------------------------------
    */
    'badge_image' => [
        'bronze' => 'starter-lane.png',
        'bronze_plus' => 'bronze-grid.png',
        'silver' => 'silver-apex.png',
        'gold' => 'gold-rush.png',
        'trophy_green' => 'elite-cup.png',
        'trophy_gold' => 'champion-run.png',
        'trophy_crystal' => 'master-class.png',
        'trophy_peak' => 'peak-of-glory.png',
    ],

];

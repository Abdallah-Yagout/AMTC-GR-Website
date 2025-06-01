<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DynamicStatsOverview;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return [
            DynamicStatsOverview::make(),
            StatsOverview::make()
        ];
    }

    public function getColumns(): int|string
    {
        return 1; // Allows widgets to use full width
    }
}

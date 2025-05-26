<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', \App\Models\User::where('type',0)->count())
                ->color('primary')
                ->icon('heroicon-o-users'),


        ];
    }
}

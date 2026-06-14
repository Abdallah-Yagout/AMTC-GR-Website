<?php

namespace App\Filament\Resources\GRCarResource\Pages;

use App\Filament\Resources\GRCarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGRCars extends ListRecords
{
    protected static string $resource = GRCarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

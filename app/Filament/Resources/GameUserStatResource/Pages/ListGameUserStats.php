<?php

namespace App\Filament\Resources\GameUserStatResource\Pages;

use App\Filament\Resources\GameUserStatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameUserStats extends ListRecords
{
    protected static string $resource = GameUserStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

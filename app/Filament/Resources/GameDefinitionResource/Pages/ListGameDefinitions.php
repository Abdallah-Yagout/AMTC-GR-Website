<?php

namespace App\Filament\Resources\GameDefinitionResource\Pages;

use App\Filament\Resources\GameDefinitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameDefinitions extends ListRecords
{
    protected static string $resource = GameDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

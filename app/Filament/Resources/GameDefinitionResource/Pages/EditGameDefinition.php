<?php

namespace App\Filament\Resources\GameDefinitionResource\Pages;

use App\Filament\Resources\GameDefinitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameDefinition extends EditRecord
{
    protected static string $resource = GameDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

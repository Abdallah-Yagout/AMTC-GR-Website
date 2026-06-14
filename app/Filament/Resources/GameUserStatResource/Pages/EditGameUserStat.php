<?php

namespace App\Filament\Resources\GameUserStatResource\Pages;

use App\Filament\Resources\GameUserStatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameUserStat extends EditRecord
{
    protected static string $resource = GameUserStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

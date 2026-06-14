<?php

namespace App\Filament\Resources\GRCarResource\Pages;

use App\Filament\Resources\GRCarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditGRCar extends EditRecord
{
    protected static string $resource = GRCarResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

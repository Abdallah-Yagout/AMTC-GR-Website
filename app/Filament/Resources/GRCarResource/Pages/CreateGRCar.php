<?php

namespace App\Filament\Resources\GRCarResource\Pages;

use App\Filament\Resources\GRCarResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateGRCar extends CreateRecord
{
    protected static string $resource = GRCarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }
}

<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTournament extends CreateRecord
{
    protected static string $resource = TournamentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['location']=json_encode($data['location']);
        return $data;
    }
}

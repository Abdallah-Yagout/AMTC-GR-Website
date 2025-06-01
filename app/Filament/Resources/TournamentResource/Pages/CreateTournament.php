<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateTournament extends CreateRecord
{
    protected static string $resource = TournamentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['title_ar'] ?? null;
        $data['description_ar'] ?? null;
        unset($data['title_ar']);
        unset($data['description_ar']);
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        /** @var \App\Models\Tournament $tournament */
        $tournament = new (static::getModel());
        foreach ($tournament->getTranslatableAttributes() as $attribute) {
            $tournament->setTranslations($attribute, [
                'en' => $data[$attribute] ?? '',
                'ar' => $data["{$attribute}_ar"] ?? '',
            ]);

            unset($data[$attribute], $data["{$attribute}_ar"]);
        }
        $tournament->fill($data);
        $tournament->save();

        return $tournament;
    }

}

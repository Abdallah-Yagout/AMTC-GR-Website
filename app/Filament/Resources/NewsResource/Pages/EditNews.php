<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->getTranslatableAttributes() as $attribute) {
            $translations = $this->record->getTranslations($attribute);

            $data[$attribute] = $translations['en'] ?? '';
            $data["{$attribute}_ar"] = $translations['ar'] ?? '';

        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->slug=Str::slug($data->title);
        foreach ($record->getTranslatableAttributes() as $attribute) {
            $record->setTranslations($attribute, [
                'en' => $data[$attribute] ?? '',
                'ar' => $data["{$attribute}_ar"] ?? '',
            ]);

            unset($data[$attribute], $data["{$attribute}_ar"]);
        }

        // Update remaining non-translatable fields
        $record->fill($data);
        $record->save();

        return $record;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

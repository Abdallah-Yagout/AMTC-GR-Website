<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTournament extends EditRecord
{
    protected static string $resource = TournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action) {
                    $record = $this->getRecord();
                    $errors = [];

                    if ($record->leaderboards()->exists()) {
                        $errors[] = 'linked leaderboards';
                    }

                    if ($record->participants()->exists()) {
                        $errors[] = 'linked participants';
                    }

                    if (!empty($errors)) {
                        Notification::make()
                            ->title('Cannot delete tournament')
                            ->body('This tournament has ' . implode(' and ', $errors) . '. Please remove them first.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
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
}

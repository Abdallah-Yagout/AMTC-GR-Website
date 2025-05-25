<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

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
}

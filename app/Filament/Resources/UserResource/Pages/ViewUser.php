<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, Model $record) {
                    // Check if user exists in participants table
                    $inParticipants = DB::table('participants')
                        ->where('user_id', $record->id)
                        ->exists();

                    // Check if user exists in leaderboard table
                    $inLeaderboard = DB::table('leaderboards')
                        ->where('user_id', $record->id)
                        ->exists();

                    // If user exists in either table, prevent hard deletion
                    if ($inParticipants || $inLeaderboard) {
                        if (method_exists($record, 'softDelete')) {
                            $record->softDelete();
                            $action->cancel();

                            // Optionally show a notification
                            Notification::make()
                                ->title('Record soft deleted (exists in related tables)')
                                ->success()
                                ->send();
                        } else {
                            throw new \Exception('Cannot soft delete - model does not support soft deletes');
                        }
                    }
                }),
            Actions\RestoreAction::make()
        ];
    }
}

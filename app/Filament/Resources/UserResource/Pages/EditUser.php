<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function fillForm(): void
    {

    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Split data: only these belong to the user
        $userData = collect($data)->only([
            'name', 'email', 'phone', 'password', 'type', 'profile_photo_path'
        ])->toArray();

        // If password is empty, don't update it
        if (empty($userData['password'])) {
            unset($userData['password']);
        } else {
            $userData['password'] = bcrypt($userData['password']);
        }

        // Update user
        $record->update($userData);

        // Get profile data (everything else)
        $profileData = collect($data)->except([
            'name', 'email', 'phone', 'password', 'type', 'password_confirmation', 'profile_photo_path'
        ])->toArray();

        // If profile does not exist, create it
        if (!$record->profile) {
            $record->profile()->create($profileData);
        } else {
            $record->profile()->update($profileData);
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

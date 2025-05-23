<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;




    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extract user-related data
        $userData = collect($data)->only([
            'name', 'email', 'phone', 'password', 'type', 'profile_photo_path'
        ])->toArray();

        // Hash password if provided
        if (!empty($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            unset($userData['password']);
        }

        // Update User model
        $record->update($userData);

        // Prepare Profile data: only include fields present in the form
        $profileFields = [
            'gender',
            'city',
            'country',
            'address',
            'date_of_birth',
            'skill_level',
            'has_ps5',
            'primary_platform',
            'weekly_hours',
            'gt7_ranking',
            'toyota_gr_knowledge',
            'favorite_car',
            'participated_before',
            'wants_training',
            'join_whatsapp',
            'heard_about',
            'preferred_time',
            'suggestions',
            'whatsapp'
        ];
        $profileData = collect($data)
            ->only($profileFields)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->toArray();

        // Update or create related Profile
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

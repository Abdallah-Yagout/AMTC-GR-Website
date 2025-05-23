<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getRecord(): \Illuminate\Database\Eloquent\Model
    {
        // Ensure profile is eager loaded
        return static::getModel()::with('profile')->find($this->getRecordRouteKey());
    }

    public function getFormColumns(): int
    {
        return 3; // Match form layout
    }
}

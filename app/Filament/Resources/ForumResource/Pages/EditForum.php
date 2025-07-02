<?php

namespace App\Filament\Resources\ForumResource\Pages;

use App\Filament\Resources\ForumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EditForum extends EditRecord
{
    protected static string $resource = ForumResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['slug']=Str::slug($data['title']);

        return $data;
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

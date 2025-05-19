<?php

namespace App\Filament\Resources\ForumResource\Pages;

use App\Filament\Resources\ForumResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateForum extends CreateRecord
{
    protected static string $resource = ForumResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']);
        // Handle Arabic title separately (don't include it in the main data)
        $arabicTitle = $data['title_ar'] ?? null;
        $arabicBody = $data['body_ar'] ?? null;
        unset($data['title_ar']);
        unset($data['body_ar']);
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        /** @var \App\Models\Forum $forum */
        $forum = new (static::getModel());

        foreach ($forum->getTranslatableAttributes() as $attribute) {
            $forum->setTranslations($attribute, [
                'en' => $data[$attribute] ?? '',
                'ar' => $data["{$attribute}_ar"] ?? '',
            ]);

            unset($data[$attribute], $data["{$attribute}_ar"]);
        }

        $forum->fill($data);
        $forum->save();

        return $forum;
    }

}

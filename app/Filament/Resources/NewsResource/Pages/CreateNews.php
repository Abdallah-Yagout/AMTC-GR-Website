<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug']=Str::slug($data['title']);
        dd($data);
//        $arabicTitle = $data['title_ar'] ?? null;
//        $arabicBody = $data['description_ar'] ?? null;
//        unset($data['title_ar']);
//        unset($data['description_ar']);
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        /** @var \App\Models\News $news */
        $news = new (static::getModel());
        dd($data);
//
//        foreach ($news->getTranslatableAttributes() as $attribute) {
//            $news->setTranslations($attribute, [
//                'en' => $data[$attribute] ?? '',
//                'ar' => $data["{$attribute}_ar"] ?? '',
//            ]);
//
//            unset($data[$attribute], $data["{$attribute}_ar"]);
//        }
//        $news->fill($data);
//        $news->save();

        return $news;
    }

}

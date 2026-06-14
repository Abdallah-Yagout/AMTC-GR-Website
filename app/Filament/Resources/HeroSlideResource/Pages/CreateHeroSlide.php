<?php

namespace App\Filament\Resources\HeroSlideResource\Pages;

use App\Filament\Resources\HeroSlideResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateHeroSlide extends CreateRecord
{
    protected static string $resource = HeroSlideResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var \App\Models\HeroSlide $heroSlide */
        $heroSlide = new (static::getModel());

        foreach ($heroSlide->getTranslatableAttributes() as $attribute) {
            $heroSlide->setTranslations($attribute, [
                'en' => $data[$attribute] ?? '',
                'ar' => $data["{$attribute}_ar"] ?? '',
            ]);

            unset($data[$attribute], $data["{$attribute}_ar"]);
        }

        $heroSlide->fill($data);
        $heroSlide->save();

        return $heroSlide;
    }
}

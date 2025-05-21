<?php

namespace App\Helpers;

class Location
{
    public static function cities ()
    {
        $cities= [
            'Aden' => 'Aden',
            'Sana\'a' => 'Sana\'a',
            'Mukalla' => 'Mukalla',
        ];
        return array_combine($cities, $cities);

    }

}

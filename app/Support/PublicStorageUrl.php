<?php

namespace App\Support;

use Illuminate\Support\Str;

final class PublicStorageUrl
{
    public static function url(?string $path): string
    {
        $trimmed = $path !== null ? trim($path) : '';

        if ($trimmed === '') {
            return '';
        }

        if (Str::startsWith($trimmed, ['http://', 'https://', '//'])) {
            return $trimmed;
        }

        return asset('storage/'.ltrim($trimmed, '/'));
    }
}

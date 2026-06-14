<?php

namespace App\Support;

use Illuminate\Support\Str;

final class PublicStorageUrl
{
    /**
     * Build a browser URL for a file on the public disk.
     * Uses a root-relative path so images work on any host (e.g. new.gryemen.com).
     */
    public static function url(?string $path): string
    {
        $trimmed = $path !== null ? trim($path) : '';

        if ($trimmed === '') {
            return '';
        }

        if (Str::startsWith($trimmed, ['http://', 'https://', '//'])) {
            return $trimmed;
        }

        return '/storage/'.ltrim(str_replace('\\', '/', $trimmed), '/');
    }
}

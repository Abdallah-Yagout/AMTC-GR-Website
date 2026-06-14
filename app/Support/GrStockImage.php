<?php

namespace App\Support;

/**
 * Public GR marketing photos used when a news / tournament / video image is missing or is an SVG.
 */
final class GrStockImage
{
    /** @var list<string> Paths under /public */
    private const FILES = [
        'img/gr-stock/01JWQNEGNWVV30QD275JJHDSW4.jpg',
        'img/gr-stock/01JZF4NWKBGJE15X1BH45YRMN7.jpg',
        'img/gr-stock/2.jpeg',
        'img/gr-stock/4.jpeg',
    ];

    public static function forStorage(?string $relativePath, string $seed): string
    {
        $trimmed = $relativePath !== null ? trim($relativePath) : '';

        if ($trimmed !== '' && ! self::pathLooksLikeSvg($trimmed)) {
            return asset('storage/'.ltrim($trimmed, '/'));
        }

        return self::stockUrl($seed);
    }

    /**
     * @param  string|null  $url  Absolute or relative image URL (e.g. preview_image accessor output)
     */
    public static function forUrl(?string $url, string $seed): string
    {
        if ($url === null || trim($url) === '' || self::pathLooksLikeSvg($url)) {
            return self::stockUrl($seed);
        }

        return $url;
    }

    public static function stockUrl(string $seed): string
    {
        $n = count(self::FILES);
        $idx = abs(crc32($seed)) % $n;

        return asset(self::FILES[$idx]);
    }

    private static function pathLooksLikeSvg(string $pathOrUrl): bool
    {
        $lower = strtolower($pathOrUrl);

        return str_ends_with($lower, '.svg') || str_contains($lower, '.svg?') || str_contains($lower, '.svg#');
    }
}

<?php

$ar = json_decode(file_get_contents(__DIR__ . '/../lang/ar.json'), true);
$missing = [];

$pattern = '/__\([\'"]([^\'"]+)[\'"]\)/';

$paths = [
    __DIR__ . '/../resources/views',
    __DIR__ . '/../app',
    __DIR__ . '/../config',
];

foreach ($paths as $base) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
    foreach ($iterator as $file) {
        if (! $file->isFile()) {
            continue;
        }
        $path = $file->getPathname();
        if (! str_ends_with($path, '.php') && ! str_ends_with($path, '.blade.php')) {
            continue;
        }
        $content = file_get_contents($path);
        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[1] as $key) {
                if (! isset($ar[$key])) {
                    $missing[$key] = true;
                }
            }
        }
    }
}

ksort($missing);
echo 'Missing keys: ' . count($missing) . PHP_EOL;
foreach (array_keys($missing) as $key) {
    echo $key . PHP_EOL;
}

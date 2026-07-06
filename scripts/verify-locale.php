<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'Default locale: ' . config('app.locale') . PHP_EOL;
echo 'ar.json keys: ' . count(json_decode(file_get_contents(__DIR__ . '/../lang/ar.json'), true)) . PHP_EOL;

foreach (['auth', 'validation', 'passwords', 'pagination'] as $file) {
    $path = __DIR__ . '/../lang/ar/' . $file . '.php';
    echo 'lang/ar/' . $file . '.php: ' . (file_exists($path) ? 'OK' : 'MISSING') . PHP_EOL;
}

app()->setLocale('ar');
echo 'Arabic validation sample: ' . __('validation.required', ['attribute' => 'email']) . PHP_EOL;

<?php

$extra = [
    'New Contact Form Submission' => 'طلب تواصل جديد',
    'This email was sent from the contact form on your website.' => 'تم إرسال هذا البريد من نموذج التواصل في موقعك.',
    'No gender data available' => 'لا تتوفر بيانات الجنس',
    'No skill level data available' => 'لا تتوفر بيانات مستوى المهارة',
    'Participants by Skill Level' => 'المشاركون حسب مستوى المهارة',
    'more' => 'المزيد',
];

$arPath = __DIR__ . '/../lang/ar.json';
$ar = json_decode(file_get_contents($arPath), true);

foreach ($extra as $key => $value) {
    $ar[$key] = $value;
}

ksort($ar, SORT_STRING);
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL);

echo 'Added ' . count($extra) . ' keys.' . PHP_EOL;

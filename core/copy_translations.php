<?php

// Copy English translations to French file with same values (to be translated later)

$enFile = __DIR__ . '/resources/lang/en.json';
$frFile = __DIR__ . '/resources/lang/fr.json';

if (!file_exists($enFile)) {
    echo "English file not found!\n";
    exit(1);
}

// Read English translations
$enTranslations = json_decode(file_get_contents($enFile), true);

if (!$enTranslations) {
    echo "Could not parse English translations!\n";
    exit(1);
}

echo "Found " . count($enTranslations) . " translation keys in English file\n";

// Copy all keys to French file (values will be the same as English for now)
$frTranslations = $enTranslations;

// Write to French file with pretty print
file_put_contents($frFile, json_encode($frTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "✓ Copied all translation keys to fr.json\n";
echo "You can now translate them via the admin panel at: /admin/language/translator/fr\n";

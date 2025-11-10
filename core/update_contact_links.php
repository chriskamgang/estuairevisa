<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Searching for contact links in database...\n\n";

$whatsappUrl = 'https://wa.me/237640387258?text=Hello,%20I%20would%20like%20to%20consult%20about%20visa%20services';
$updated = 0;

// Check pages table
$pages = DB::table('pages')->get();

foreach ($pages as $page) {
    if (isset($page->content) && $page->content) {
        if (strpos($page->content, '/contact') !== false || strpos($page->content, 'contact') !== false) {
            echo "📄 Found contact link in page: {$page->name} (ID: {$page->id})\n";

            $newContent = str_replace(
                ['/contact"', "/contact'", 'href="/contact"', "href='/contact'"],
                [$whatsappUrl . '"', $whatsappUrl . "'", 'href="' . $whatsappUrl . '"', "href='" . $whatsappUrl . "'"],
                $page->content
            );

            if ($newContent !== $page->content) {
                DB::table('pages')->where('id', $page->id)->update(['content' => $newContent]);
                echo "   ✅ Updated!\n";
                $updated++;
            }
        }
    }
}

// Check section_data table
$sections = DB::table('section_data')->get();

foreach ($sections as $section) {
    if (isset($section->data) && $section->data) {
        if (strpos($section->data, '/contact') !== false || strpos($section->data, 'contact') !== false) {
            echo "📦 Found contact link in section: {$section->key}\n";

            $newData = str_replace(
                ['/contact"', "/contact'", '"/contact"', "'/contact'"],
                [$whatsappUrl . '"', $whatsappUrl . "'", '"' . $whatsappUrl . '"', "'" . $whatsappUrl . "'"],
                $section->data
            );

            if ($newData !== $section->data) {
                DB::table('section_data')->where('id', $section->id)->update(['data' => $newData]);
                echo "   ✅ Updated!\n";
                $updated++;
            }
        }
    }
}

echo "\n" . str_repeat('=', 50) . "\n";
if ($updated > 0) {
    echo "✅ Successfully updated {$updated} items!\n";
    echo "📱 WhatsApp: +237640387258\n";
} else {
    echo "⚠️  No contact links found to update.\n";
}
echo str_repeat('=', 50) . "\n";

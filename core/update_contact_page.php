<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Page;

// Get the contact page
$page = Page::where('slug', 'contact')->first();

if (!$page) {
    echo "Contact page not found!\n";
    exit(1);
}

echo "Found contact page (ID: {$page->id})\n";

// Current HTML (extract the contact items section)
$currentHtml = $page->html;

// Add the WhatsApp section after the Email Address section
$whatsappSection = '<div class="col-lg-12"><div class="contact-item" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; border: none;"><h5 class="title" style="color: white;"><i class="fab fa-whatsapp"></i> Contact via WhatsApp</h5><p class="mb-3" style="color: rgba(255,255,255,0.9);">+237 640 387 258</p><a href="https://wa.me/237640387258?text=Hello,%20I%20would%20like%20to%20consult%20about%20visa%20services" target="_blank" class="btn btn-light btn-sm" style="color: #25D366; font-weight: 600;"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a></div></div>';

// Find the position after the Email Address section
$emailSectionEnd = 'info@example.com</p></div></div>';

if (strpos($currentHtml, $emailSectionEnd) !== false) {
    // Insert WhatsApp section after Email section
    $newHtml = str_replace(
        $emailSectionEnd . '</div>',
        $emailSectionEnd . $whatsappSection . '</div>',
        $currentHtml
    );

    $page->html = $newHtml;
    $page->save();

    echo "✓ WhatsApp section added successfully!\n";
    echo "Please refresh your browser (Ctrl+F5 or Cmd+Shift+R)\n";
} else {
    echo "Could not find the email section to insert after.\n";
    echo "Please check the page structure.\n";
}

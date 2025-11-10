<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class UpdateContactLinksToWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:contact-to-whatsapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all contact page links to WhatsApp link (+237640387258)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to update contact links to WhatsApp...');

        $whatsappUrl = 'https://wa.me/237640387258?text=Hello,%20I%20would%20like%20to%20consult%20about%20visa%20services';

        // Patterns to search and replace
        $patterns = [
            '/contact',
            'contact',
            '/\/contact/',
            '"/contact"',
            "'/contact'",
            'href="/contact"',
            "href='/contact'",
        ];

        $updated = 0;

        // Update in pages table
        $pages = Page::all();

        foreach ($pages as $page) {
            if ($page->content) {
                $originalContent = $page->content;
                $newContent = $originalContent;

                // Replace various contact link patterns
                $newContent = str_replace('/contact"', $whatsappUrl . '"', $newContent);
                $newContent = str_replace("/contact'", $whatsappUrl . "'", $newContent);
                $newContent = str_replace('href="/contact"', 'href="' . $whatsappUrl . '"', $newContent);
                $newContent = str_replace("href='/contact'", "href='" . $whatsappUrl . "'", $newContent);

                // Also replace route('contact') if exists
                $newContent = str_replace("route('contact')", "'" . $whatsappUrl . "'", $newContent);
                $newContent = str_replace('route("contact")', '"' . $whatsappUrl . '"', $newContent);

                if ($originalContent !== $newContent) {
                    $page->content = $newContent;
                    $page->save();
                    $updated++;
                    $this->line("✅ Updated page: {$page->name}");
                }
            }
        }

        // Update in section_data table
        $sections = DB::table('section_data')->get();

        foreach ($sections as $section) {
            if ($section->data) {
                $data = json_decode($section->data, true);
                $originalData = json_encode($data);

                // Recursively replace contact links in data
                $data = $this->replaceInArray($data, '/contact', $whatsappUrl);

                $newData = json_encode($data);

                if ($originalData !== $newData) {
                    DB::table('section_data')
                        ->where('id', $section->id)
                        ->update(['data' => $newData]);
                    $updated++;
                    $this->line("✅ Updated section: {$section->key}");
                }
            }
        }

        if ($updated > 0) {
            $this->info("✅ Successfully updated {$updated} items!");
            $this->info("WhatsApp number: +237640387258");
        } else {
            $this->warn("No contact links found to update.");
        }

        // Clear cache
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $this->info("Cache cleared!");

        return 0;
    }

    /**
     * Recursively replace values in array
     */
    private function replaceInArray($array, $search, $replace)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->replaceInArray($value, $search, $replace);
            } elseif (is_string($value)) {
                $array[$key] = str_replace($search, $replace, $value);
            }
        }
        return $array;
    }
}

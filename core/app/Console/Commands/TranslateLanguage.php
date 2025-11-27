<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TranslateLanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:translate
                            {target : The target language short code}
                            {--source= : The source language short code (default: default language)}
                            {--force : Overwrite existing translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically translate a language using DeepL API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $targetCode = $this->argument('target');
        $sourceCode = $this->option('source');
        $force = $this->option('force');

        // Get target language
        $targetLanguage = Language::where('short_code', $targetCode)->first();

        if (!$targetLanguage) {
            $this->error("Target language '{$targetCode}' not found in database.");
            return 1;
        }

        // Get source language
        if ($sourceCode) {
            $sourceLanguage = Language::where('short_code', $sourceCode)->first();
            if (!$sourceLanguage) {
                $this->error("Source language '{$sourceCode}' not found in database.");
                return 1;
            }
        } else {
            $sourceLanguage = Language::where('is_default', 1)->first();
            if (!$sourceLanguage) {
                $this->error("No default language found. Please specify a source language with --source option.");
                return 1;
            }
        }

        $this->info("Translating from {$sourceLanguage->name} ({$sourceLanguage->short_code}) to {$targetLanguage->name} ({$targetLanguage->short_code})...");

        // Check source file
        $sourcePath = resource_path("lang/{$sourceLanguage->short_code}.json");

        if (!file_exists($sourcePath)) {
            $this->error("Source language file not found: {$sourcePath}");
            return 1;
        }

        $sourceContent = json_decode(file_get_contents($sourcePath), true);

        if (empty($sourceContent)) {
            $this->error("Source language file is empty.");
            return 1;
        }

        // Check target file
        $targetPath = resource_path("lang/{$targetLanguage->short_code}.json");
        $targetContent = file_exists($targetPath) ? json_decode(file_get_contents($targetPath), true) : [];

        if (!$force && !empty($targetContent)) {
            if (!$this->confirm("Target language file already exists. Do you want to merge translations?", true)) {
                $this->info('Translation cancelled.');
                return 0;
            }
        }

        try {
            // Initialize translation service
            $translationService = new TranslationService();

            // Show DeepL usage before translation
            $usageBefore = $translationService->getUsage();
            $this->info("DeepL Usage: {$usageBefore['character_count']} / {$usageBefore['character_limit']} characters ({$usageBefore['percentage_used']}%)");

            $totalKeys = count($sourceContent);
            $this->info("Total keys to translate: {$totalKeys}");

            // Create progress bar
            $progressBar = $this->output->createProgressBar($totalKeys);
            $progressBar->start();

            // Translate with progress callback
            $translations = $translationService->translateFile(
                $sourcePath,
                $targetLanguage->short_code,
                $sourceLanguage->short_code,
                function ($current, $total) use ($progressBar) {
                    $progressBar->setProgress($current);
                }
            );

            $progressBar->finish();
            $this->newLine(2);

            // Merge with existing translations if not forcing
            if (!$force && !empty($targetContent)) {
                $translations = array_merge($targetContent, $translations);
                $this->info("Merged with existing translations.");
            }

            // Save translations
            file_put_contents($targetPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->info("Successfully translated {$totalKeys} keys to {$targetLanguage->name}!");

            // Show DeepL usage after translation
            $usageAfter = $translationService->getUsage();
            $charactersUsed = $usageAfter['character_count'] - $usageBefore['character_count'];
            $this->info("Characters used: {$charactersUsed}");
            $this->info("New DeepL Usage: {$usageAfter['character_count']} / {$usageAfter['character_limit']} characters ({$usageAfter['percentage_used']}%)");

            return 0;

        } catch (\Exception $e) {
            $this->error("Translation failed: {$e->getMessage()}");
            Log::error('Language translation command error: ' . $e->getMessage(), [
                'target' => $targetCode,
                'source' => $sourceCode,
            ]);
            return 1;
        }
    }
}

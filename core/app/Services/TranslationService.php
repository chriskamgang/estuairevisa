<?php

namespace App\Services;

use DeepL\Translator;
use DeepL\DeepLException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected $translator;
    protected $cacheEnabled;
    protected $cacheTtl;
    protected $cachePrefix;

    public function __construct()
    {
        // Try to get API key from database first, fallback to .env
        $generalSetting = \App\Models\GeneralSetting::first();

        if ($generalSetting && $generalSetting->deepl_api_key && $generalSetting->deepl_status) {
            $apiKey = $generalSetting->deepl_api_key;
            $apiType = $generalSetting->deepl_api_type;
        } else {
            $apiKey = config('deepl.api_key');
            $apiType = config('deepl.api_type');
        }

        if (empty($apiKey)) {
            throw new \Exception('DeepL API key not configured. Please configure it in Admin Settings or add DEEPL_API_KEY to your .env file.');
        }

        // Determine API URL based on type
        $options = [];
        if ($apiType === 'free') {
            $options['server_url'] = 'https://api-free.deepl.com';
        } elseif (config('deepl.api_url') !== 'https://api.deepl.com') {
            $options['server_url'] = config('deepl.api_url');
        }

        $this->translator = new Translator($apiKey, $options);
        $this->cacheEnabled = config('deepl.cache.enabled', true);
        $this->cacheTtl = config('deepl.cache.ttl', 86400 * 30);
        $this->cachePrefix = config('deepl.cache.prefix', 'deepl_translation_');
    }

    /**
     * Translate a single text
     *
     * @param string $text
     * @param string $targetLang
     * @param string|null $sourceLang
     * @return string
     */
    public function translate(string $text, string $targetLang, ?string $sourceLang = null): string
    {
        if (empty($text)) {
            return $text;
        }

        // Convert language codes
        $targetLang = $this->mapLanguageCode($targetLang);
        $sourceLang = $sourceLang ? $this->mapLanguageCode($sourceLang) : null;

        // Check cache
        if ($this->cacheEnabled) {
            $cacheKey = $this->getCacheKey($text, $targetLang, $sourceLang);
            $cached = Cache::get($cacheKey);

            if ($cached !== null) {
                return $cached;
            }
        }

        try {
            $options = [
                'formality' => config('deepl.options.formality', 'default'),
            ];

            if ($sourceLang) {
                $options['source_lang'] = $sourceLang;
            }

            $result = $this->translator->translateText($text, null, $targetLang, $options);
            $translation = $result->text;

            // Cache the result
            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $translation, $this->cacheTtl);
            }

            return $translation;

        } catch (DeepLException $e) {
            Log::error('DeepL Translation Error: ' . $e->getMessage(), [
                'text' => substr($text, 0, 100),
                'target' => $targetLang,
                'source' => $sourceLang,
            ]);

            // Return original text if translation fails
            return $text;
        }
    }

    /**
     * Translate multiple texts in batch
     *
     * @param array $texts
     * @param string $targetLang
     * @param string|null $sourceLang
     * @return array
     */
    public function translateBatch(array $texts, string $targetLang, ?string $sourceLang = null): array
    {
        $targetLang = $this->mapLanguageCode($targetLang);
        $sourceLang = $sourceLang ? $this->mapLanguageCode($sourceLang) : null;

        $translations = [];
        $textsToTranslate = [];
        $indexMap = [];

        // Check cache for each text
        foreach ($texts as $index => $text) {
            if (empty($text)) {
                $translations[$index] = $text;
                continue;
            }

            if ($this->cacheEnabled) {
                $cacheKey = $this->getCacheKey($text, $targetLang, $sourceLang);
                $cached = Cache::get($cacheKey);

                if ($cached !== null) {
                    $translations[$index] = $cached;
                    continue;
                }
            }

            $textsToTranslate[] = $text;
            $indexMap[] = $index;
        }

        // Translate remaining texts
        if (!empty($textsToTranslate)) {
            try {
                $options = [
                    'formality' => config('deepl.options.formality', 'default'),
                ];

                if ($sourceLang) {
                    $options['source_lang'] = $sourceLang;
                }

                $results = $this->translator->translateText($textsToTranslate, null, $targetLang, $options);

                foreach ($results as $i => $result) {
                    $originalIndex = $indexMap[$i];
                    $translation = $result->text;
                    $translations[$originalIndex] = $translation;

                    // Cache the result
                    if ($this->cacheEnabled) {
                        $cacheKey = $this->getCacheKey($textsToTranslate[$i], $targetLang, $sourceLang);
                        Cache::put($cacheKey, $translation, $this->cacheTtl);
                    }
                }

            } catch (DeepLException $e) {
                Log::error('DeepL Batch Translation Error: ' . $e->getMessage());

                // Use original texts for failed translations
                foreach ($indexMap as $i => $originalIndex) {
                    if (!isset($translations[$originalIndex])) {
                        $translations[$originalIndex] = $textsToTranslate[$i];
                    }
                }
            }
        }

        ksort($translations);
        return array_values($translations);
    }

    /**
     * Translate an entire language file
     *
     * @param string $sourceFile
     * @param string $targetLang
     * @param string|null $sourceLang
     * @param callable|null $progressCallback
     * @return array
     */
    public function translateFile(string $sourceFile, string $targetLang, ?string $sourceLang = null, ?callable $progressCallback = null): array
    {
        $sourceContent = json_decode(file_get_contents($sourceFile), true);

        if (!is_array($sourceContent)) {
            throw new \Exception('Invalid JSON file format');
        }

        $translations = [];
        $total = count($sourceContent);
        $current = 0;
        $batchSize = 50; // DeepL allows up to 50 texts per request

        $keys = array_keys($sourceContent);
        $values = array_values($sourceContent);

        for ($i = 0; $i < $total; $i += $batchSize) {
            $batch = array_slice($values, $i, $batchSize);
            $batchKeys = array_slice($keys, $i, $batchSize);

            $translatedBatch = $this->translateBatch($batch, $targetLang, $sourceLang);

            foreach ($batchKeys as $index => $key) {
                $translations[$key] = $translatedBatch[$index];
                $current++;

                if ($progressCallback) {
                    $progressCallback($current, $total);
                }
            }

            // Small delay to respect API rate limits
            if ($i + $batchSize < $total) {
                usleep(100000); // 100ms delay
            }
        }

        return $translations;
    }

    /**
     * Get usage information from DeepL API
     *
     * @return array
     */
    public function getUsage(): array
    {
        try {
            $usage = $this->translator->getUsage();

            return [
                'character_count' => $usage->character->count,
                'character_limit' => $usage->character->limit,
                'percentage_used' => $usage->character->limit > 0
                    ? round(($usage->character->count / $usage->character->limit) * 100, 2)
                    : 0,
            ];
        } catch (DeepLException $e) {
            Log::error('DeepL Usage Error: ' . $e->getMessage());
            return [
                'character_count' => 0,
                'character_limit' => 0,
                'percentage_used' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get supported target languages
     *
     * @return array
     */
    public function getSupportedLanguages(): array
    {
        try {
            $languages = $this->translator->getTargetLanguages();
            return collect($languages)->map(function ($lang) {
                return [
                    'code' => $lang->code,
                    'name' => $lang->name,
                ];
            })->toArray();
        } catch (DeepLException $e) {
            Log::error('DeepL Languages Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Map application language codes to DeepL codes
     *
     * @param string $langCode
     * @return string
     */
    protected function mapLanguageCode(string $langCode): string
    {
        $map = config('deepl.language_map', []);
        return $map[$langCode] ?? strtoupper($langCode);
    }

    /**
     * Generate cache key
     *
     * @param string $text
     * @param string $targetLang
     * @param string|null $sourceLang
     * @return string
     */
    protected function getCacheKey(string $text, string $targetLang, ?string $sourceLang): string
    {
        return $this->cachePrefix . md5($text . '|' . $targetLang . '|' . ($sourceLang ?? 'auto'));
    }

    /**
     * Clear translation cache
     *
     * @return bool
     */
    public function clearCache(): bool
    {
        try {
            Cache::flush();
            return true;
        } catch (\Exception $e) {
            Log::error('Cache Clear Error: ' . $e->getMessage());
            return false;
        }
    }
}

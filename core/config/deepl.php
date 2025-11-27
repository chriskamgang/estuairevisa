<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DeepL API Key
    |--------------------------------------------------------------------------
    |
    | Your DeepL API authentication key. You can get it from:
    | https://www.deepl.com/pro-api
    |
    | Free plan: 500,000 characters/month
    | Pro plan: Starting at €5.49/month
    |
    */
    'api_key' => env('DEEPL_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | DeepL API URL
    |--------------------------------------------------------------------------
    |
    | The API URL endpoint. Use the free API URL for free accounts.
    | Free: https://api-free.deepl.com
    | Pro: https://api.deepl.com
    |
    */
    'api_url' => env('DEEPL_API_URL', 'https://api-free.deepl.com'),

    /*
    |--------------------------------------------------------------------------
    | Default Source Language
    |--------------------------------------------------------------------------
    |
    | The default source language for translations.
    | Set to null to let DeepL auto-detect the language.
    |
    */
    'default_source' => env('DEEPL_DEFAULT_SOURCE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    |
    | Map your application's language codes to DeepL language codes.
    | DeepL supports: EN, FR, DE, ES, IT, PT, NL, PL, RU, JA, ZH, etc.
    |
    */
    'language_map' => [
        'en' => 'EN',
        'fr' => 'FR',
        'Fr' => 'FR',
        'sp' => 'ES',
        'es' => 'ES',
        'de' => 'DE',
        'it' => 'IT',
        'pt' => 'PT-PT',
        'nl' => 'NL',
        'pl' => 'PL',
        'ru' => 'RU',
        'ja' => 'JA',
        'zh' => 'ZH',
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Options
    |--------------------------------------------------------------------------
    |
    | Default options for translation requests.
    |
    */
    'options' => [
        'formality' => env('DEEPL_FORMALITY', 'default'), // default, more, less
        'preserve_formatting' => env('DEEPL_PRESERVE_FORMATTING', true),
        'split_sentences' => env('DEEPL_SPLIT_SENTENCES', 'on'), // on, off, nonewlines
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Enable caching to reduce API calls and costs.
    |
    */
    'cache' => [
        'enabled' => env('DEEPL_CACHE_ENABLED', true),
        'ttl' => env('DEEPL_CACHE_TTL', 86400 * 30), // 30 days in seconds
        'prefix' => 'deepl_translation_',
    ],
];

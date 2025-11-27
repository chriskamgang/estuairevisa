<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function index()
    {
        $pageTitle = "Language Settings";
        $languages = Language::latest()->get();
        return view('backend.language.index', compact('languages', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'language' => 'required|unique:languages,name',
            'short_code' => 'required|unique:languages,short_code',
        ]);

        $language = Language::first();

        if ($language) {
            $is_default = 0;
        } else {
            $is_default = 1;
        }

        Language::create([
            'name' => $request->language,
            'short_code' => $request->short_code,
            'is_default' => $is_default
        ]);


        $path = resource_path('lang/');

        fopen($path . "$request->short_code.json", "w");

        file_put_contents($path . "$request->short_code.json", '{}');


        $notify[] = ['success', 'Language Created Successfully'];

        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $language = Language::findOrFail($request->id);

        $request->validate([
            'language' => 'required|unique:languages,name,' . $language->id,
            'short_code' => 'required|unique:languages,short_code,' . $language->id,
        ]);

        if ($request->is_default) {
            $language->is_default = $request->is_default;
            $language->save();

            DB::table('languages')->where('id', '!=', $language->id)->update(['is_default' => 0]);
        }

        $language->update([
            'name' => $request->language,
            'short_code' => $request->short_code
        ]);

        $path = resource_path() . "/lang/$language->short_code.json";


        if (file_exists($path)) {

            $file_data = json_encode(file_get_contents($path));

            unlink($path);

            file_put_contents($path, json_decode($file_data));
        } else {

            fopen(resource_path() . "/lang/$request->short_code.json", "w");

            file_put_contents(resource_path() . "/lang/$request->short_code.json", '{}');
        }



        $notify[] = ['success', 'Language Updated Successfully'];

        return back()->withNotify($notify);
    }

    public function delete(Request $request)
    {
        $language = Language::findOrFail($request->id);

        if ($language->is_default) {
            $notify[] = ['error', 'Default Language Can not Deleted'];
            return back()->withNotify($notify);
        }

        $path = resource_path() . "/lang/$language->short_code.json";

        if (file_exists($path)) {
            unlink($path);
        }

        if (session('locale') == $language->short_code) {

            session()->forget('locale');
        }


        $language->delete();


        $notify[] = ['success', 'Language Deleted Successfully'];

        return back()->withNotify($notify);
    }


    public function transalate(Request $request)
    {
        $pageTitle = "Language Translator";

        $languages = Language::where('short_code', '!=', $request->lang)->get();

        $language = Language::where('short_code', $request->lang)->firstOrFail();

        $translators = collect(json_decode(file_get_contents(resource_path() . "/lang/$language->short_code.json"), true));

        $all = $translators;

        $translators = $this->paginate($translators, 20);

        return view('backend.language.translate', compact('translators', 'languages', 'all', 'pageTitle'));
    }


    public function transalateUpate(Request $request)
    {
        $language = Language::where('short_code', $request->lang)->firstOrFail();

        if ($request->key == null && $request->value == null) {
            $request->merge(['key' => ['home']]);
            $request->merge(['value' => ['home']]);
        }


        $translator = array_filter(array_combine($request->key, $request->value));

        file_put_contents(resource_path() . "/lang/$language->short_code.json", json_encode($translator));

        return redirect()->back()->with('success', 'Updated Successfully');
    }


    public function paginate(Collection $results, $showPerPage)
    {
        $pageNumber = Paginator::resolveCurrentPage('page');

        $totalPageNumber = $results->count();

        return self::paginator($results->forPage($pageNumber, $showPerPage), $totalPageNumber, $showPerPage, $pageNumber, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }


    public function changeLang(Request $request)
    {
        App::setLocale($request->lang);

        session()->put('locale', $request->lang);

        return redirect()->back()->with('success', __('Successfully Changed Language'));
    }

    /**
     * Auto-translate a language from default language using DeepL
     */
    public function autoTranslate(Request $request)
    {
        $request->validate([
            'target_lang' => 'required|exists:languages,short_code',
            'source_lang' => 'nullable|exists:languages,short_code',
        ]);

        try {
            $targetLanguage = Language::where('short_code', $request->target_lang)->firstOrFail();

            // Get source language (default or specified)
            $sourceLanguage = $request->source_lang
                ? Language::where('short_code', $request->source_lang)->firstOrFail()
                : Language::where('is_default', 1)->firstOrFail();

            // Load source language file
            $sourcePath = resource_path("lang/{$sourceLanguage->short_code}.json");

            if (!file_exists($sourcePath)) {
                $notify[] = ['error', 'Source language file not found'];
                return back()->withNotify($notify);
            }

            $sourceContent = json_decode(file_get_contents($sourcePath), true);

            if (empty($sourceContent)) {
                $notify[] = ['error', 'Source language file is empty'];
                return back()->withNotify($notify);
            }

            // Initialize translation service
            $translationService = new TranslationService();

            // Translate the content
            $translations = $translationService->translateFile(
                $sourcePath,
                $targetLanguage->short_code,
                $sourceLanguage->short_code
            );

            // Save translations to target language file
            $targetPath = resource_path("lang/{$targetLanguage->short_code}.json");
            file_put_contents($targetPath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $notify[] = ['success', "Successfully translated {$sourceLanguage->name} to {$targetLanguage->name} using DeepL"];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            Log::error('Auto-translate error: ' . $e->getMessage());
            $notify[] = ['error', 'Translation failed: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /**
     * Auto-translate a specific key/value using DeepL
     */
    public function translateKey(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'source_text' => 'required|string',
            'target_lang' => 'required|exists:languages,short_code',
            'source_lang' => 'nullable|exists:languages,short_code',
        ]);

        try {
            $targetLanguage = Language::where('short_code', $request->target_lang)->firstOrFail();

            // Get source language
            $sourceLang = $request->source_lang
                ? $request->source_lang
                : Language::where('is_default', 1)->value('short_code');

            // Initialize translation service
            $translationService = new TranslationService();

            // Translate the text
            $translation = $translationService->translate(
                $request->source_text,
                $targetLanguage->short_code,
                $sourceLang
            );

            // Load current translations
            $targetPath = resource_path("lang/{$targetLanguage->short_code}.json");
            $currentTranslations = file_exists($targetPath)
                ? json_decode(file_get_contents($targetPath), true)
                : [];

            // Add the new translation
            $currentTranslations[$request->key] = $translation;

            // Save updated translations
            file_put_contents($targetPath, json_encode($currentTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return response()->json([
                'success' => true,
                'translation' => $translation,
                'message' => 'Translation completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Translate key error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Translation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get DeepL usage statistics
     */
    public function getDeeplUsage()
    {
        try {
            $translationService = new TranslationService();
            $usage = $translationService->getUsage();

            return response()->json([
                'success' => true,
                'usage' => $usage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supported DeepL languages
     */
    public function getSupportedLanguages()
    {
        try {
            $translationService = new TranslationService();
            $languages = $translationService->getSupportedLanguages();

            return response()->json([
                'success' => true,
                'languages' => $languages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear translation cache
     */
    public function clearTranslationCache()
    {
        try {
            $translationService = new TranslationService();
            $translationService->clearCache();

            $notify[] = ['success', 'Translation cache cleared successfully'];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            Log::error('Clear cache error: ' . $e->getMessage());
            $notify[] = ['error', 'Failed to clear cache'];
            return back()->withNotify($notify);
        }
    }
}

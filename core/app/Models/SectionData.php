<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionData extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'object',
        'translations' => 'array'
    ];
    protected $guarded = [];

    /**
     * Get translated value for a specific field
     */
    public function getTranslated($field, $locale = null)
    {
        if (!$locale) {
            $locale = session('lang', 'en');
        }

        // Return translation if exists, otherwise return original value
        if (isset($this->translations[$locale][$field])) {
            return $this->translations[$locale][$field];
        }

        // Fallback to English translation
        if ($locale !== 'en' && isset($this->translations['en'][$field])) {
            return $this->translations['en'][$field];
        }

        // Fallback to original data
        return $this->data->$field ?? '';
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'country_ids' => 'array',
        'title_translations' => 'array',
        'heading_translations' => 'array',
        'short_description_translations' => 'array'
    ];

    function scopeActive($query)
    {
        return $query->where('status',1);
    }

    /**
     * Get translated title based on current locale
     */
    public function getTranslatedTitle()
    {
        $locale = session('locale', 'en');
        return $this->title_translations[$locale] ?? $this->title;
    }

    /**
     * Get translated heading based on current locale
     */
    public function getTranslatedHeading()
    {
        $locale = session('locale', 'en');
        return $this->heading_translations[$locale] ?? $this->heading;
    }

    /**
     * Get translated short description based on current locale
     */
    public function getTranslatedShortDescription()
    {
        $locale = session('locale', 'en');
        return $this->short_description_translations[$locale] ?? $this->short_description;
    }
}

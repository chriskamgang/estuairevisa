<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    function scopeHeaders()
    {
        $language = selectedLanguage();
        return $this->where('menu_type', 'headers')->whereHas('page', function ($q) use ($language) {
            $q->where('language_id', $language->id);
        });
    }

    function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    function scopeFooters()
    {
        $language = selectedLanguage();
        return $this->where('menu_type', 'footers')->whereHas('page', function ($q) use ($language) {
            $q->where('language_id', $language->id);
        });
    }

    function scopeCompany()
    {
        $language = selectedLanguage();
        return $this->footers()->where('footer_part_type', 'company')->whereHas('page', function ($q) use ($language) {
            $q->where('language_id', $language->id);
        });
    }

    function scopeQuickLink()
    {
        $language = selectedLanguage();
        return $this->footers()->where('footer_part_type', 'quick_link')->whereHas('page', function ($q) use ($language) {
            $q->where('language_id', $language->id);
        });
    }
}

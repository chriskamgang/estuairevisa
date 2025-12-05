<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'name_translations' => 'array'
    ];

    function scopeActive($query)
    {
        return $query->where('status',1);
    }

    function scopeSlider($query)
    {
        return $query->where('is_slider',1);
    }

    /**
     * Get translated name based on current locale
     */
    public function getTranslatedName()
    {
        $locale = session('locale', 'en');
        return $this->name_translations[$locale] ?? $this->name;
    }
}

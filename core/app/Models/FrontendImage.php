<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'section',
        'label',
        'image',
        'description',
        'order'
    ];

    /**
     * Get image by key
     */
    public static function getByKey($key, $default = null)
    {
        $image = self::where('key', $key)->first();
        return $image ? $image->image : $default;
    }

    /**
     * Get all images by section
     */
    public static function getBySection($section)
    {
        return self::where('section', $section)->orderBy('order')->get();
    }
}

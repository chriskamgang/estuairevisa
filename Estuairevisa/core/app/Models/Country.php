<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function scopeActive($query)
    {
        return $query->where('status',1);
    }

    function scopeSlider($query)
    {
        return $query->where('is_slider',1);
    }
}

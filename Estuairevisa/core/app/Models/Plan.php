<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'country_ids' => 'array'
    ];

    function scopeActive($query)
    {
        return $query->where('status',1);
    }
    
    function country()
    {
        return $this->belongsTo(Country::class,'for_country')->withDefault();
    }
}

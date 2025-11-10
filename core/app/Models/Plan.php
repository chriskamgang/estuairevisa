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
}

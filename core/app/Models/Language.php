<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $guarded = [];

    function pages()
    {
        return $this->hasMany(Page::class,'language_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaStatusLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    function log()
    {
        return $this->belongsTo(CheckoutLog::class,'apply_id');
    }
}

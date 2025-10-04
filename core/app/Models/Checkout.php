<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $guarded = [];


    function logs()
    {
        return $this->hasMany(CheckoutLog::class,'checkout_id');
    }


    function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    function paymentStatus()
    {
        $status = "<span class='badge badge-secondary'>N/A</span>";

        if ($this->status == 0) {
            $status = "<span class='badge badge-warning'>Pending</span>";
        } elseif ($this->status == 1) {
            $status = "<span class='badge badge-success'>Completed</span>";
        } elseif ($this->status == 2) {
            $status = "<span class='badge badge-danger'>Rejected</span>";
        }

        return $status;
    }
}

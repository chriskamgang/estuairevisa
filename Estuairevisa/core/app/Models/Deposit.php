<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $casts = [
        'payment_proof' => 'array'
    ];

    protected $guarded = [];

    public function gateway()
    {
        return $this->belongsTo(Gateway::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
    
    public function status()
    {
  
        if ($this->payment_status == 1) {
            return '<span class="badge bg-success">Complete</span>';
        } elseif ($this->payment_status == 2) {
            return '<span class="badge bg-warning text-dark">Pending</span>';
        } elseif ($this->payment_status == 3) {
            return '<span class="badge bg-danger">Rejected</span>';
        }else{
            return  "N/A";
        }
    }
}

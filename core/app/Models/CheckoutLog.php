<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutLog extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'personal_info' => 'object',
        'files' => 'object'
    ];

    function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'proccessing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'complete');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }


    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }


    function status()
    {
        $status = "<span class='badge badge-secondary'>N/A</span>";

        switch ($this->status) {
            case 'draft':
                $status = "<span class='badge badge-danger'>Draft</span>";
                break;
            case 'pending':
                $status = "<span class='badge badge-warning'>Pending</span>";
                break;
            case 'under_review':
                $status = "<span class='badge badge-info'>Under Review</span>";
                break;
            case 'proccessing':
                $status = "<span class='badge badge-info'>Proccessing</span>";
                break;
            case 'issues':
                $status = "<span class='badge badge-primary'>Issues</span>";
                break;
            case 'complete':
                $status = "<span class='badge badge-success'>Complete</span>";
                break;
            case 'reject':
                $status = "<span class='badge badge-danger'>Rejected</span>";
                break;
            case 'shipped':
                $status = "<span class='badge badge-dark'>Shipped</span>";
                break;
        }

        return $status;
    }
}

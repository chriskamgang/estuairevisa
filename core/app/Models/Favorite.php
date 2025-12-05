<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorable_type',
        'favorable_id',
        'collection',
        'notes'
    ];

    /**
     * Get the user that owns the favorite.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorable model (polymorphic relation)
     */
    public function favorable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by collection
     */
    public function scopeByCollection($query, $collection)
    {
        return $query->where('collection', $collection);
    }

    /**
     * Scope to filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('favorable_type', $type);
    }
}

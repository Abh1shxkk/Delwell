<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'quote',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active quotes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get quotes ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get all active quotes ordered by sort_order
     */
    public static function getActiveQuotes()
    {
        return self::active()->ordered()->get();
    }
}

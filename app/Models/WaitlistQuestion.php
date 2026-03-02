<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitlistQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_type',
        'field_name',
        'options',
        'is_required',
        'is_active',
        'sort_order',
        'max_selections',
        'placeholder',
        'help_text',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'max_selections' => 'integer',
    ];

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered questions
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get questions for this section
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'section', 'name');
    }

    /**
     * Scope for active sections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sections
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}

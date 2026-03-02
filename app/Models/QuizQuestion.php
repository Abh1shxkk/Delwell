<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'question_id',
        'section',
        'question',
        'type',
        'options',
        'order',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];

    // Scope for active questions
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    // Get questions by section
    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }
}

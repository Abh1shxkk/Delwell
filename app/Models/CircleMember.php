<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircleMember extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'relationship',
        'invitation_id',
        'joined_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * Get the main user (who owns the circle)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the circle member (who joined the circle)
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Get the original invitation
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(CircleInvitation::class, 'invitation_id');
    }
}

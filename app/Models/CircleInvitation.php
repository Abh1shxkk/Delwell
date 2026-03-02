<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CircleInvitation extends Model
{
    protected $fillable = [
        'inviter_id',
        'invitee_name',
        'invitee_email',
        'relationship',
        'token',
        'status',
        'expires_at',
        'accepted_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Generate a unique token for the invitation
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Check if invitation is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if invitation is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Get the inviter (user who sent the invitation)
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
}

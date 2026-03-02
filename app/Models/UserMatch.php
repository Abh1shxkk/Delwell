<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMatch extends Model
{
    protected $fillable = [
        'user_id',
        'matched_user_id',
        'status',
        'is_circle_pick',
        'matched_at'
    ];

    protected function casts(): array
    {
        return [
            'is_circle_pick' => 'boolean',
            'matched_at' => 'datetime',
        ];
    }

    /**
     * Get the user who initiated the match
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who was matched
     */
    public function matchedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }

    /**
     * Check if two users are matched (both flagged interest)
     */
    public static function areMatched($userId1, $userId2): bool
    {
        return self::where([
            ['user_id', $userId1],
            ['matched_user_id', $userId2],
            ['status', 'matched']
        ])->exists() && self::where([
            ['user_id', $userId2],
            ['matched_user_id', $userId1],
            ['status', 'matched']
        ])->exists();
    }

    /**
     * Get match status between two users
     */
    public static function getMatchStatus($userId, $matchedUserId): ?string
    {
        $match = self::where('user_id', $userId)
            ->where('matched_user_id', $matchedUserId)
            ->first();
        
        return $match ? $match->status : null;
    }
}

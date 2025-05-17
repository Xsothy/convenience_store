<?php

namespace App\Models;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'is_default',
        'additional_info',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Scope a query to only include addresses for a specific session.
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope a query to only include addresses for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

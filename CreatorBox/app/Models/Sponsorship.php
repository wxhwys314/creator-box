<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'supporter_id',
        'plan_id',
        'creator_id',
        'monthly_amount',
        'status',
        'cancelled_at',
        'expires_at'
    ];

    protected $casts = [
        'monthly_amount' => 'integer',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Get the supporter (user)
     */
    public function supporter()
    {
        return $this->belongsTo(User::class, 'supporter_id');
    }

    /**
     * Get the plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Check if sponsorship is active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }

    /**
     * Check if sponsorship is cancelled but still valid until expiry
     */
    public function isCancelledButValid()
    {
        return $this->status === 'cancelled' && $this->expires_at->isFuture();
    }

    /**
     * Check if user can access content (active or cancelled)
     */
    public function canAccessContent()
    {
        return $this->isActive() || $this->isCancelledButValid();
    }

    /**
     * Calculate expiry date (end of current month)
     */
    public static function calculateExpiryDate()
    {
        return now()->endOfMonth();
    }

    /**
     * Scope active sponsorships
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('expires_at', '>', now());
    }

    /**
     * Scope valid sponsorships (can access content)
     */
    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'active')
              ->orWhere(function($q2) {
                  $q2->where('status', 'cancelled')
                     ->where('expires_at', '>', now());
              });
        })->where('expires_at', '>', now());
    }
}
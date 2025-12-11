<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//sponsorship model

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'plan_name',
        'monthly_amount',
        'cover',
        'description',
        'is_active',
    ];

    protected $casts = [
        'monthly_amount' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * creator of the plan
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * sponsorships associated with the plan
     */
    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class);
    }
}
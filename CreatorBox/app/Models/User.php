<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'creator_id',
        'avatar',
        'bio',
        'cover',
        'coin_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is a creator
     */
    public function isCreator()
    {
        return $this->role === 'creator';
    }

    /**
     * Check if user is a fan
     */
    public function isFan()
    {
        return $this->role === 'fan';
    }


    //follow system
    /**
     * user is following (fan → creator)
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * user is followed by (creator ← fan)
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * check if following a certain user
     */
    public function isFollowing($userId)
    {
        return $this->followings()->where('following_id', $userId)->exists();
    }

    /**
     * check if followed by a certain user
     */
    public function isFollowedBy($userId)
    {
        return $this->followers()->where('follower_id', $userId)->exists();
    }


    //posts system
    /**
     * posts of user
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * comments of user
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * likes of user
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    //plans system
    /**
     * plans of user (as creator)
     */
    public function plans()
    {
        return $this->hasMany(Plan::class, 'creator_id');
    }

    //sponsorships system
    /**
     * Get user's sponsorship relationships (as supporter)
     */
    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class, 'supporter_id');
    }

    /**
     * Check if user is supporting a specific plan
     */
    public function isSupportingPlan($planId): bool
    {
        return $this->sponsorships()
            ->where('plan_id', $planId)
            ->where(function($query) {
                $query->where('status', 'active')
                    ->orWhere(function($q) {
                        $q->where('status', 'cancelled')
                            ->where('expires_at', '>', now());
                    });
            })
            ->exists();
    }

    /**
     * Get user's active sponsorships
     */
    public function activeSponsorships()
    {
        return $this->sponsorships()->active();
    }

    //daily check in system
    /**
     * Check if user has checked in today
     */
    public function hasCheckedInToday(): bool
    {
        return $this->last_checkin_date === today()->toDateString();
    }

    /**
     * Perform daily check-in
     */
    public function checkin(): void
    {
        $this->last_checkin_date = today();
        $this->coin_balance += 1;
        $this->save();
    }
}

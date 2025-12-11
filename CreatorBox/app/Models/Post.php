<?php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'cover',
        'title',
        'content_text',
        'media_assets',
        'visibility',
        'supporter_min_amount',
        'comment_permission',
        'status',
        'likes_count',
        'published_at',
    ];

    protected $casts = [
        'media_assets' => 'array',
        'published_at' => 'datetime',
        'supporter_min_amount' => 'integer',
        'likes_count' => 'integer',
    ];

    /**
     * Relationship: post owner.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: creator of the post.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: post comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Relationship: post likes.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Scope: only published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope: filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accessor: get media URLs.
     */
    public function getMediaUrlsAttribute()
    {
        if (!$this->media_assets) {
            return [];
        }

        return array_map(function($asset) {
            return Storage::url($asset['path']);
        }, $this->media_assets);
    }

    /**
     * Check if the post is visible to the specified user
     */
    public function isVisibleTo(User $user = null)
    {
        // Draft posts are only visible to the creator
        if ($this->status === 'draft') {
            return $user && $user->id === $this->user_id;
        }

        // Unpublished posts are not visible
        if (!$this->published_at || $this->published_at->isFuture()) {
            return $user && $user->id === $this->user_id;
        }

        // Public posts are visible to all
        if ($this->visibility === 'all') {
            return true;
        }

        // Supporter-only posts require authentication and sponsorship check
        if ($this->visibility === 'supporters') {
            if (!$user) {
                return false;
            }

            // The post author can view their own content
            if ($user->id === $this->user_id) {
                return true;
            }

            // Check if the user is a supporter with sufficient sponsorship level
            return $this->canBeAccessedBySupporter($user);
        }

        return false;
    }

    /**
     * Check if a supporter can access this post based on sponsorship amount
     */
    private function canBeAccessedBySupporter(User $user)
    {
        // Get user's active sponsorship for this creator
        $sponsorship = $user->sponsorships()
            ->where('creator_id', $this->user_id)
            ->where(function($query) {
                $query->where('status', 'active')
                    ->orWhere(function($q) {
                        $q->where('status', 'cancelled')
                            ->where('expires_at', '>', now());
                    });
            })
            ->orderByDesc('monthly_amount')
            ->first();

        if (!$sponsorship) {
            return false;
        }

        return $sponsorship->monthly_amount >= $this->supporter_min_amount;
    }

    /**
     * Check if the current user has comment permission
     */
    public function canComment(User $user = null)
    {
        if (!$user) {
            return false;
        }

        // Cannot comment if the post is not published
        if ($this->status !== 'published' || !$this->published_at || $this->published_at->isFuture()) {
            return false;
        }

        switch ($this->comment_permission) {
            case 'all':
                return true;
            case 'supporters':
                // Check if the user can access the post (same logic as visibility)
                return $this->isVisibleTo($user);
            case 'none':
                return false;
            default:
                return false;
        }
    }

    /**
     * Check if the current user has liked the post
     */
    public function isLikedBy(User $user = null)
    {
        if (!$user) {
            return false;
        }
        
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
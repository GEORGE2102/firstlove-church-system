<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'target_audience',
        'priority',
        'is_active',
        'published_at',
        'expires_at',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Priority constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Target audience constants
     */
    const AUDIENCE_ALL = 'all';
    const AUDIENCE_MEMBERS = 'members';
    const AUDIENCE_LEADERS = 'leaders';
    const AUDIENCE_PASTORS = 'pastors';
    const AUDIENCE_TREASURERS = 'treasurers';
    const AUDIENCE_ADMINS = 'admins';

    /**
     * Get all available priorities
     */
    public static function getPriorities()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_URGENT => 'Urgent',
        ];
    }

    /**
     * Get all available target audiences
     */
    public static function getTargetAudiences()
    {
        return [
            self::AUDIENCE_ALL => 'All Users',
            self::AUDIENCE_MEMBERS => 'Members Only',
            self::AUDIENCE_LEADERS => 'Fellowship Leaders',
            self::AUDIENCE_PASTORS => 'Pastors',
            self::AUDIENCE_TREASURERS => 'Treasurers',
            self::AUDIENCE_ADMINS => 'Administrators',
        ];
    }

    /**
     * Get the user who created this announcement
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users who have read this announcement
     */
    public function readBy()
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
                    ->withTimestamps();
    }

    /**
     * Check if announcement is published
     */
    public function isPublished()
    {
        return $this->is_active && 
               $this->published_at && 
               $this->published_at <= now() &&
               (!$this->expires_at || $this->expires_at >= now());
    }

    /**
     * Check if announcement is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    /**
     * Check if announcement is high priority or urgent
     */
    public function isHighPriority()
    {
        return in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    /**
     * Check if announcement is urgent
     */
    public function isUrgent()
    {
        return $this->priority === self::PRIORITY_URGENT;
    }

    /**
     * Check if user can see this announcement
     */
    public function canBeSeenBy(User $user)
    {
        if (!$this->isPublished()) {
            return false;
        }

        return match($this->target_audience) {
            self::AUDIENCE_ALL => true,
            self::AUDIENCE_MEMBERS => true, // All logged-in users
            self::AUDIENCE_LEADERS => $user->isLeader() || $user->isPastor() || $user->isAdmin(),
            self::AUDIENCE_PASTORS => $user->isPastor() || $user->isAdmin(),
            self::AUDIENCE_TREASURERS => $user->isTreasurer() || $user->isAdmin(),
            self::AUDIENCE_ADMINS => $user->isAdmin(),
            default => false,
        };
    }

    /**
     * Mark announcement as read by user
     */
    public function markAsReadBy(User $user)
    {
        if (!$this->readBy()->where('user_id', $user->id)->exists()) {
            $this->readBy()->attach($user->id);
        }
    }

    /**
     * Check if announcement has been read by user
     */
    public function hasBeenReadBy(User $user)
    {
        return $this->readBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope to get published announcements
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                    ->where('published_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    /**
     * Scope to get announcements for specific audience
     */
    public function scopeForAudience($query, $audience)
    {
        return $query->where('target_audience', $audience)
                    ->orWhere('target_audience', self::AUDIENCE_ALL);
    }

    /**
     * Scope to get announcements visible to user
     */
    public function scopeVisibleTo($query, User $user)
    {
        return $query->published()
                    ->where(function($q) use ($user) {
                        $q->where('target_audience', self::AUDIENCE_ALL)
                          ->orWhere('target_audience', self::AUDIENCE_MEMBERS);
                        
                        if ($user->isLeader()) {
                            $q->orWhere('target_audience', self::AUDIENCE_LEADERS);
                        }
                        
                        if ($user->isPastor()) {
                            $q->orWhere('target_audience', self::AUDIENCE_PASTORS);
                        }
                        
                        if ($user->isTreasurer()) {
                            $q->orWhere('target_audience', self::AUDIENCE_TREASURERS);
                        }
                        
                        if ($user->isAdmin()) {
                            $q->orWhere('target_audience', self::AUDIENCE_ADMINS);
                        }
                    });
    }

    /**
     * Scope to get high priority announcements
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    /**
     * Scope to get urgent announcements
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', self::PRIORITY_URGENT);
    }

    /**
     * Scope to get recent announcements
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    /**
     * Get priority badge class for UI
     */
    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'badge-secondary',
            self::PRIORITY_NORMAL => 'badge-primary',
            self::PRIORITY_HIGH => 'badge-warning',
            self::PRIORITY_URGENT => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Get formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y \a\t g:i A') : 'Not Published';
    }

    /**
     * Get excerpt of content
     */
    public function getExcerptAttribute($length = 150)
    {
        return strlen($this->content) > $length 
            ? substr($this->content, 0, $length) . '...'
            : $this->content;
    }
} 
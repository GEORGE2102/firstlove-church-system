<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'fellowship_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * User roles constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_PASTOR = 'pastor';
    const ROLE_LEADER = 'leader';
    const ROLE_TREASURER = 'treasurer';
    const ROLE_MEMBER = 'member';

    /**
     * Get all available roles
     */
    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_PASTOR => 'Pastor (Overseer)',
            self::ROLE_LEADER => 'Fellowship Leader',
            self::ROLE_TREASURER => 'Treasurer',
            self::ROLE_MEMBER => 'Member',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Check if user is pastor
     */
    public function isPastor()
    {
        return $this->hasRole(self::ROLE_PASTOR);
    }

    /**
     * Check if user is fellowship leader
     */
    public function isLeader()
    {
        return $this->hasRole(self::ROLE_LEADER);
    }

    /**
     * Check if user is treasurer
     */
    public function isTreasurer()
    {
        return $this->hasRole(self::ROLE_TREASURER);
    }

    /**
     * Get the fellowship that the user belongs to
     */
    public function fellowship()
    {
        return $this->belongsTo(Fellowship::class);
    }

    /**
     * Get fellowships that this user leads (if they are a leader)
     */
    public function leaderFellowships()
    {
        return $this->hasMany(Fellowship::class, 'leader_id');
    }

    /**
     * Get fellowships under this pastor's oversight
     */
    public function pastorFellowships()
    {
        return $this->hasMany(Fellowship::class, 'pastor_id');
    }

    /**
     * Get attendance records for this user
     */
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get offering submissions by this user
     */
    public function offerings()
    {
        return $this->hasMany(Offering::class, 'submitted_by');
    }

    /**
     * Get announcements created by this user
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Get the user's full name with role
     */
    public function getFullNameWithRoleAttribute()
    {
        $roleName = self::getRoles()[$this->role] ?? ucfirst($this->role);
        return $this->name . ' (' . $roleName . ')';
    }

    /**
     * Scope to filter users by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to get active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 
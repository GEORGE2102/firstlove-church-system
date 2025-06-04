<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fellowship extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'meeting_day',
        'meeting_time',
        'leader_id',
        'pastor_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meeting_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the fellowship leader
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get the pastor overseeing this fellowship
     */
    public function pastor()
    {
        return $this->belongsTo(User::class, 'pastor_id');
    }

    /**
     * Get all members of this fellowship
     */
    public function members()
    {
        return $this->hasMany(User::class, 'fellowship_id');
    }

    /**
     * Get active members of this fellowship
     */
    public function activeMembers()
    {
        return $this->hasMany(User::class, 'fellowship_id')->where('is_active', true);
    }

    /**
     * Get attendance records for this fellowship
     */
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get recent attendance records
     */
    public function recentAttendance($limit = 10)
    {
        return $this->attendanceRecords()
                    ->orderBy('attendance_date', 'desc')
                    ->limit($limit);
    }

    /**
     * Get offering records for this fellowship
     */
    public function offerings()
    {
        return $this->hasMany(Offering::class);
    }

    /**
     * Get recent offering records
     */
    public function recentOfferings($limit = 10)
    {
        return $this->offerings()
                    ->orderBy('offering_date', 'desc')
                    ->limit($limit);
    }

    /**
     * Get the latest attendance record
     */
    public function latestAttendance()
    {
        return $this->attendanceRecords()
                    ->orderBy('attendance_date', 'desc')
                    ->first();
    }

    /**
     * Get total members count
     */
    public function getTotalMembersAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Get active members count
     */
    public function getActiveMembersCountAttribute()
    {
        return $this->activeMembers()->count();
    }

    /**
     * Get average attendance for a period
     */
    public function getAverageAttendance($startDate = null, $endDate = null)
    {
        $query = $this->attendanceRecords();
        
        if ($startDate) {
            $query->where('attendance_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('attendance_date', '<=', $endDate);
        }
        
        return $query->avg('attendance_count') ?? 0;
    }

    /**
     * Get total offerings for a period
     */
    public function getTotalOfferings($startDate = null, $endDate = null)
    {
        $query = $this->offerings()->where('status', 'confirmed');
        
        if ($startDate) {
            $query->where('offering_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('offering_date', '<=', $endDate);
        }
        
        return $query->sum('amount') ?? 0;
    }

    /**
     * Scope to get active fellowships
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by pastor
     */
    public function scopeByPastor($query, $pastorId)
    {
        return $query->where('pastor_id', $pastorId);
    }

    /**
     * Scope to filter by leader
     */
    public function scopeByLeader($query, $leaderId)
    {
        return $query->where('leader_id', $leaderId);
    }

    /**
     * Get fellowship meeting day name
     */
    public function getMeetingDayNameAttribute()
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday', 
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday'
        ];
        
        return $days[$this->meeting_day] ?? 'Not Set';
    }

    /**
     * Get formatted meeting time
     */
    public function getFormattedMeetingTimeAttribute()
    {
        return $this->meeting_time ? $this->meeting_time->format('H:i A') : 'Not Set';
    }
} 
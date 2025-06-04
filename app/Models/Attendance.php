<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fellowship_id',
        'user_id',
        'attendance_date',
        'attendance_count',
        'bible_study_topic',
        'notes',
        'recorded_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Get the fellowship this attendance record belongs to
     */
    public function fellowship()
    {
        return $this->belongsTo(Fellowship::class);
    }

    /**
     * Get the user who attended (if individual record)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who recorded this attendance
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scope to filter by fellowship
     */
    public function scopeByFellowship($query, $fellowshipId)
    {
        return $query->where('fellowship_id', $fellowshipId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by current week
     */
    public function scopeCurrentWeek($query)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        return $query->whereBetween('attendance_date', [$startOfWeek, $endOfWeek]);
    }

    /**
     * Scope to filter by current month
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('attendance_date', now()->month)
                    ->whereYear('attendance_date', now()->year);
    }

    /**
     * Scope to get recent records
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('attendance_date', 'desc')->limit($limit);
    }

    /**
     * Get formatted attendance date
     */
    public function getFormattedDateAttribute()
    {
        return $this->attendance_date->format('l, F j, Y');
    }

    /**
     * Get attendance percentage (if fellowship has members)
     */
    public function getAttendancePercentageAttribute()
    {
        $totalMembers = $this->fellowship->active_members_count;
        
        if ($totalMembers > 0) {
            return round(($this->attendance_count / $totalMembers) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Get the week number for this attendance record
     */
    public function getWeekNumberAttribute()
    {
        return $this->attendance_date->weekOfYear;
    }

    /**
     * Check if this is a high attendance record (above 80%)
     */
    public function isHighAttendance()
    {
        return $this->attendance_percentage >= 80;
    }

    /**
     * Check if this is a low attendance record (below 50%)
     */
    public function isLowAttendance()
    {
        return $this->attendance_percentage < 50;
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Fellowship;
use App\Models\Attendance;
use App\Models\Offering;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard (redirects to role-specific dashboard)
     */
    public function index()
    {
        $user = auth()->user();
        
        // Redirect to appropriate dashboard based on role
        return match($user->role) {
            'admin' => $this->adminDashboard(),
            'pastor' => $this->pastorDashboard(),
            'leader' => $this->leaderDashboard(),
            'treasurer' => $this->treasurerDashboard(),
            'member' => $this->memberDashboard(),
            default => $this->memberDashboard(),
        };
    }

    /**
     * Admin Dashboard
     */
    protected function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_fellowships' => Fellowship::count(),
            'active_fellowships' => Fellowship::where('is_active', true)->count(),
            'total_attendance_this_month' => Attendance::currentMonth()->sum('attendance_count'),
            'total_offerings_this_month' => Offering::currentMonth()->confirmed()->sum('amount'),
            'pending_offerings' => Offering::pending()->count(),
            'recent_announcements' => Announcement::published()->recent(5)->get(),
        ];

        $recentActivity = [
            'recent_attendance' => Attendance::with(['fellowship', 'recordedBy'])
                ->recent(10)->get(),
            'recent_offerings' => Offering::with(['fellowship', 'submittedBy'])
                ->recent(10)->get(),
        ];

        return view('dashboard.admin', compact('stats', 'recentActivity'));
    }

    /**
     * Pastor Dashboard
     */
    public function pastorDashboard()
    {
        $user = auth()->user();
        $fellowships = Fellowship::where('pastor_id', $user->id)->with(['leader', 'members'])->get();
        
        $stats = [
            'my_fellowships' => $fellowships->count(),
            'total_members' => $fellowships->sum(function($fellowship) {
                return $fellowship->activeMembers()->count();
            }),
            'this_week_attendance' => Attendance::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->currentWeek()->sum('attendance_count'),
            'this_month_offerings' => Offering::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->currentMonth()->confirmed()->sum('amount'),
            'pending_offerings' => Offering::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->pending()->count(),
        ];

        $recentActivity = [
            'recent_attendance' => Attendance::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->with(['fellowship', 'recordedBy'])->recent(5)->get(),
            'recent_offerings' => Offering::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->with(['fellowship', 'submittedBy'])->recent(5)->get(),
        ];

        return view('dashboard.pastor', compact('stats', 'fellowships', 'recentActivity'));
    }

    /**
     * Fellowship Leader Dashboard
     */
    public function leaderDashboard()
    {
        $user = auth()->user();
        $fellowships = Fellowship::where('leader_id', $user->id)->with(['pastor', 'members'])->get();
        
        $stats = [
            'my_fellowships' => $fellowships->count(),
            'total_members' => $fellowships->sum(function($fellowship) {
                return $fellowship->activeMembers()->count();
            }),
            'this_week_attendance' => Attendance::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->currentWeek()->sum('attendance_count'),
            'this_month_offerings' => Offering::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->currentMonth()->sum('amount'),
            'pending_submissions' => Offering::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->pending()->count(),
        ];

        $recentActivity = [
            'recent_attendance' => Attendance::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->with(['fellowship'])->recent(5)->get(),
            'recent_offerings' => Offering::whereIn('fellowship_id', $fellowships->pluck('id'))
                ->with(['fellowship'])->recent(5)->get(),
        ];

        return view('dashboard.leader', compact('stats', 'fellowships', 'recentActivity'));
    }

    /**
     * Treasurer Dashboard
     */
    public function treasurerDashboard()
    {
        $stats = [
            'pending_offerings' => Offering::pending()->count(),
            'confirmed_this_month' => Offering::currentMonth()->confirmed()->count(),
            'total_amount_this_month' => Offering::currentMonth()->confirmed()->sum('amount'),
            'rejected_this_month' => Offering::currentMonth()->where('status', 'rejected')->count(),
        ];

        $pendingOfferings = Offering::pending()
            ->with(['fellowship', 'submittedBy'])
            ->orderBy('created_at', 'asc')
            ->get();

        $recentConfirmed = Offering::confirmed()
            ->with(['fellowship', 'submittedBy', 'confirmedBy'])
            ->recent(10)
            ->get();

        return view('dashboard.treasurer', compact('stats', 'pendingOfferings', 'recentConfirmed'));
    }

    /**
     * Member Dashboard
     */
    public function memberDashboard()
    {
        $user = auth()->user();
        $fellowship = $user->fellowship;
        
        $stats = [];
        $recentActivity = [];
        
        if ($fellowship) {
            $stats = [
                'my_fellowship' => $fellowship->name,
                'fellowship_members' => $fellowship->activeMembers()->count(),
                'last_attendance' => $fellowship->latestAttendance()?->attendance_count ?? 0,
                'my_attendance_rate' => $this->calculateUserAttendanceRate($user),
            ];

            $recentActivity = [
                'recent_attendance' => Attendance::where('fellowship_id', $fellowship->id)
                    ->recent(5)->get(),
                'recent_announcements' => Announcement::visibleTo($user)->recent(5)->get(),
            ];
        }

        return view('dashboard.member', compact('stats', 'fellowship', 'recentActivity'));
    }

    /**
     * Get dashboard statistics via API
     */
    public function getStats(Request $request)
    {
        $user = auth()->user();
        
        return match($user->role) {
            'admin' => $this->getAdminStats(),
            'pastor' => $this->getPastorStats(),
            'leader' => $this->getLeaderStats(),
            'treasurer' => $this->getTreasurerStats(),
            'member' => $this->getMemberStats(),
        };
    }

    /**
     * Calculate user attendance rate
     */
    private function calculateUserAttendanceRate(User $user)
    {
        if (!$user->fellowship) {
            return 0;
        }

        $totalSessions = Attendance::where('fellowship_id', $user->fellowship->id)
            ->where('attendance_date', '>=', Carbon::now()->subMonths(3))
            ->count();

        $userAttendances = Attendance::where('fellowship_id', $user->fellowship->id)
            ->where('attendance_date', '>=', Carbon::now()->subMonths(3))
            ->where('attendance_count', '>', 0)
            ->count();

        return $totalSessions > 0 ? round(($userAttendances / $totalSessions) * 100, 1) : 0;
    }

    /**
     * Admin statistics for API
     */
    private function getAdminStats()
    {
        return [
            'users' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'by_role' => User::selectRaw('role, count(*) as count')
                    ->groupBy('role')->pluck('count', 'role'),
            ],
            'fellowships' => [
                'total' => Fellowship::count(),
                'active' => Fellowship::where('is_active', true)->count(),
            ],
            'attendance' => [
                'this_month' => Attendance::currentMonth()->sum('attendance_count'),
                'last_month' => Attendance::whereMonth('attendance_date', Carbon::now()->subMonth())
                    ->sum('attendance_count'),
            ],
            'offerings' => [
                'this_month' => Offering::currentMonth()->confirmed()->sum('amount'),
                'pending' => Offering::pending()->count(),
            ],
        ];
    }

    /**
     * Pastor statistics for API
     */
    private function getPastorStats()
    {
        $user = auth()->user();
        $fellowshipIds = Fellowship::where('pastor_id', $user->id)->pluck('id');

        return [
            'fellowships' => Fellowship::where('pastor_id', $user->id)->count(),
            'members' => User::whereIn('fellowship_id', $fellowshipIds)->count(),
            'attendance_this_week' => Attendance::whereIn('fellowship_id', $fellowshipIds)
                ->currentWeek()->sum('attendance_count'),
            'offerings_this_month' => Offering::whereIn('fellowship_id', $fellowshipIds)
                ->currentMonth()->confirmed()->sum('amount'),
        ];
    }

    /**
     * Leader statistics for API
     */
    private function getLeaderStats()
    {
        $user = auth()->user();
        $fellowshipIds = Fellowship::where('leader_id', $user->id)->pluck('id');

        return [
            'fellowships' => Fellowship::where('leader_id', $user->id)->count(),
            'members' => User::whereIn('fellowship_id', $fellowshipIds)->count(),
            'attendance_this_week' => Attendance::whereIn('fellowship_id', $fellowshipIds)
                ->currentWeek()->sum('attendance_count'),
            'offerings_pending' => Offering::whereIn('fellowship_id', $fellowshipIds)
                ->pending()->count(),
        ];
    }

    /**
     * Treasurer statistics for API
     */
    private function getTreasurerStats()
    {
        return [
            'pending_offerings' => Offering::pending()->count(),
            'confirmed_this_month' => Offering::currentMonth()->confirmed()->sum('amount'),
            'total_offerings' => Offering::confirmed()->sum('amount'),
        ];
    }

    /**
     * Member statistics for API
     */
    private function getMemberStats()
    {
        $user = auth()->user();
        
        if (!$user->fellowship) {
            return ['error' => 'No fellowship assigned'];
        }

        return [
            'fellowship_name' => $user->fellowship->name,
            'fellowship_members' => $user->fellowship->activeMembers()->count(),
            'last_attendance' => $user->fellowship->latestAttendance()?->attendance_count ?? 0,
            'my_attendance_rate' => $this->calculateUserAttendanceRate($user),
        ];
    }
} 
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FellowshipController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Auth::routes();

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    });
    
    // Fellowship Management Routes
    Route::resource('fellowships', FellowshipController::class);
    Route::post('fellowships/{fellowship}/assign-leader', [FellowshipController::class, 'assignLeader'])->name('fellowships.assign-leader');
    
    // Attendance Routes
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    
    // Offering Routes
    Route::get('offerings', [OfferingController::class, 'index'])->name('offerings.index');
    Route::get('offerings/create', [OfferingController::class, 'create'])->name('offerings.create');
    Route::post('offerings', [OfferingController::class, 'store'])->name('offerings.store');
    Route::get('offerings/{offering}', [OfferingController::class, 'show'])->name('offerings.show');
    Route::get('offerings/{offering}/edit', [OfferingController::class, 'edit'])->name('offerings.edit');
    Route::put('offerings/{offering}', [OfferingController::class, 'update'])->name('offerings.update');
    Route::delete('offerings/{offering}', [OfferingController::class, 'destroy'])->name('offerings.destroy');
    
    // Treasurer-specific offering routes
    Route::middleware(['role:treasurer,admin'])->group(function () {
        Route::post('offerings/{offering}/confirm', [OfferingController::class, 'confirm'])->name('offerings.confirm');
        Route::post('offerings/{offering}/reject', [OfferingController::class, 'reject'])->name('offerings.reject');
    });
    
    // Announcement Routes
    Route::resource('announcements', AnnouncementController::class);
    Route::post('announcements/{announcement}/mark-read', [AnnouncementController::class, 'markAsRead'])->name('announcements.mark-read');
    
    // Report Routes
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('reports/offerings', [ReportController::class, 'offerings'])->name('reports.offerings');
    Route::get('reports/fellowships', [ReportController::class, 'fellowships'])->name('reports.fellowships');
    Route::get('reports/export/attendance', [ReportController::class, 'exportAttendance'])->name('reports.export.attendance');
    Route::get('reports/export/offerings', [ReportController::class, 'exportOfferings'])->name('reports.export.offerings');
    
    // Pastor Dashboard Routes
    Route::middleware(['role:pastor,admin'])->group(function () {
        Route::get('pastor/dashboard', [DashboardController::class, 'pastorDashboard'])->name('pastor.dashboard');
        Route::get('pastor/fellowships', [FellowshipController::class, 'pastorFellowships'])->name('pastor.fellowships');
    });
    
    // Fellowship Leader Dashboard Routes
    Route::middleware(['role:leader,admin'])->group(function () {
        Route::get('leader/dashboard', [DashboardController::class, 'leaderDashboard'])->name('leader.dashboard');
    });
    
    // Treasurer Dashboard Routes
    Route::middleware(['role:treasurer,admin'])->group(function () {
        Route::get('treasurer/dashboard', [DashboardController::class, 'treasurerDashboard'])->name('treasurer.dashboard');
    });
    
    // Member Dashboard Routes
    Route::middleware(['role:member,leader,pastor,treasurer,admin'])->group(function () {
        Route::get('member/dashboard', [DashboardController::class, 'memberDashboard'])->name('member.dashboard');
    });
});

// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('fellowships/{fellowship}/members', [FellowshipController::class, 'getMembers']);
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('reports/charts/attendance', [ReportController::class, 'attendanceChart']);
    Route::get('reports/charts/offerings', [ReportController::class, 'offeringsChart']);
}); 
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FellowshipController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\OfferingController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Dashboard API
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('dashboard/recent-activity', [DashboardController::class, 'getRecentActivity']);
    
    // Fellowship API
    Route::get('fellowships/{fellowship}/members', [FellowshipController::class, 'getMembers']);
    Route::get('fellowships/{fellowship}/stats', [FellowshipController::class, 'getStats']);
    
    // Attendance API
    Route::get('attendance/charts/{fellowship}', [AttendanceController::class, 'getCharts']);
    Route::get('attendance/trends/{fellowship}', [AttendanceController::class, 'getTrends']);
    
    // Offering API
    Route::get('offerings/charts/{fellowship}', [OfferingController::class, 'getCharts']);
    Route::get('offerings/summary/{fellowship}', [OfferingController::class, 'getSummary']);
    
    // Reports API
    Route::get('reports/charts/attendance', [ReportController::class, 'attendanceChart']);
    Route::get('reports/charts/offerings', [ReportController::class, 'offeringsChart']);
    Route::get('reports/export/attendance', [ReportController::class, 'exportAttendanceApi']);
    Route::get('reports/export/offerings', [ReportController::class, 'exportOfferingsApi']);
}); 
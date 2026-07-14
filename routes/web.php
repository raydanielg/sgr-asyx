<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingLotController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Parking Lots - owner, admin_manager
    Route::resource('parking-lots', ParkingLotController::class);

    // Stations - owner, admin_manager
    Route::resource('stations', StationController::class);

    // Booths - owner, admin_manager
    Route::resource('booths', BoothController::class);

    // Targets - owner, admin_manager
    Route::resource('targets', TargetController::class);

    // Daily Reports - all roles (controller handles scoping)
    Route::get('reports', [DailyReportController::class, 'index'])->name('reports.index');
    Route::get('reports/create', [DailyReportController::class, 'create'])->name('reports.create');
    Route::post('reports', [DailyReportController::class, 'store'])->name('reports.store');
    Route::get('reports/{report}', [DailyReportController::class, 'show'])->name('reports.show');
    Route::get('reports/{report}/edit', [DailyReportController::class, 'edit'])->name('reports.edit');
    Route::put('reports/{report}', [DailyReportController::class, 'update'])->name('reports.update');
    Route::delete('reports/{report}', [DailyReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('reports/{report}/approve', [DailyReportController::class, 'approve'])->name('reports.approve')->middleware('role:owner,admin_manager');
    Route::post('reports/{report}/reject', [DailyReportController::class, 'reject'])->name('reports.reject')->middleware('role:owner,admin_manager');

    // Users - owner only
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Audit Logs - owner, admin_manager
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});


<?php

namespace App\Http\Controllers;

use App\Models\ParkingLot;
use App\Models\Station;
use App\Models\DailyReport;
use App\Models\DailyCollection;
use App\Models\Target;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isSupervisor()) {
            return $this->supervisorDashboard($user);
        }

        return $this->managementDashboard($user);
    }

    private function supervisorDashboard($user)
    {
        $stationIds = $user->stations()->pluck('id');

        $todayReports = DailyReport::whereIn('station_id', $stationIds)
            ->whereDate('report_date', today())->count();

        $monthRevenue = DailyCollection::whereHas('dailyReport', function ($q) use ($stationIds) {
            $q->whereIn('station_id', $stationIds)->where('status', 'approved');
        })->whereMonth('date_in', now()->month)->sum('amount_collected');

        $pendingReports = DailyReport::whereIn('station_id', $stationIds)->where('status', 'pending')->count();
        $approvedReports = DailyReport::whereIn('station_id', $stationIds)->where('status', 'approved')->count();
        $rejectedReports = DailyReport::whereIn('station_id', $stationIds)->where('status', 'rejected')->count();

        $recentReports = DailyReport::whereIn('station_id', $stationIds)
            ->with('station', 'collections')->latest()->take(8)->get();

        $stations = $user->stations()->with('parkingLot', 'booths')->get();

        $monthlyTarget = Target::where('scope', 'station')
            ->where('scope_id', $stationIds->first() ?? 0)
            ->where('metric', 'revenue_tzs')
            ->where('period', 'monthly')
            ->where('start_date', '<=', today())
            ->where(function ($q) { $q->whereNull('end_date')->orWhere('end_date', '>=', today()); })
            ->orderByDesc('start_date')->first();

        return view('dashboard.supervisor', compact(
            'todayReports', 'monthRevenue', 'pendingReports', 'approvedReports',
            'rejectedReports', 'recentReports', 'stations', 'monthlyTarget'
        ));
    }

    private function managementDashboard($user)
    {
        $totalLots = ParkingLot::count();
        $totalStations = Station::count();
        $activeSupervisors = User::where('role', 'supervisor')->where('status', 'active')->count();

        $todayRevenue = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->whereDate('date_in', today())->sum('amount_collected');

        $weekRevenue = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->whereBetween('date_in', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount_collected');

        $monthRevenue = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->whereMonth('date_in', now()->month)->sum('amount_collected');

        $yearRevenue = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->whereYear('date_in', now()->year)->sum('amount_collected');

        $totalDeposited = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->whereMonth('date_in', now()->month)->sum('amount_deposited');

        $totalDifference = $monthRevenue - $totalDeposited;
        $totalVehicles = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->whereMonth('date_in', now()->month)->count();

        $pendingApprovals = DailyReport::where('status', 'pending')->count();

        $variances = DailyCollection::whereHas('dailyReport', function ($q) {
            $q->where('status', 'approved');
        })->where('difference', '!=', 0)
          ->whereMonth('date_in', now()->month)
          ->with('dailyReport.station', 'booth')
          ->latest()->take(10)->get();

        $recentReports = DailyReport::with('station', 'submittedBy', 'collections')
            ->latest()->take(8)->get();

        $revenueLabels = [];
        $revenueCollected = [];
        $revenueDeposited = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenueLabels[] = $date->format('M d');
            $revenueCollected[] = (float) DailyCollection::whereHas('dailyReport', function ($q) {
                $q->where('status', 'approved');
            })->whereDate('date_in', $date)->sum('amount_collected');
            $revenueDeposited[] = (float) DailyCollection::whereHas('dailyReport', function ($q) {
                $q->where('status', 'approved');
            })->whereDate('date_in', $date)->sum('amount_deposited');
        }

        $stationComparison = Station::withSum(['dailyReports as approved_collections' => function ($q) {
            $q->where('status', 'approved');
        }], 'vehicle_count')->get();

        $contractualTarget = Target::where('target_type', 'contractual')
            ->where('metric', 'revenue_tzs')->where('period', 'monthly')
            ->where('start_date', '<=', today())
            ->where(function ($q) { $q->whereNull('end_date')->orWhere('end_date', '>=', today()); })
            ->orderByDesc('start_date')->first();

        $companyTarget = Target::where('target_type', 'company_set')
            ->where('metric', 'revenue_tzs')->where('period', 'monthly')
            ->where('start_date', '<=', today())
            ->where(function ($q) { $q->whereNull('end_date')->orWhere('end_date', '>=', today()); })
            ->orderByDesc('start_date')->first();

        $totalUsers = User::count();
        $recentUsers = User::latest()->take(6)->get();

        return view('dashboard.management', compact(
            'totalLots', 'totalStations', 'activeSupervisors',
            'todayRevenue', 'weekRevenue', 'monthRevenue', 'yearRevenue',
            'totalDeposited', 'totalDifference', 'totalVehicles',
            'pendingApprovals', 'variances', 'recentReports',
            'revenueLabels', 'revenueCollected', 'revenueDeposited',
            'stationComparison', 'contractualTarget', 'companyTarget',
            'totalUsers', 'recentUsers'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = now()->toDateString();
        $thisWeek = now()->subDays(7);

        $totalUsers = User::count();
        $activeToday = User::whereDate('updated_at', $today)->count();
        $newUsers = User::where('created_at', '>=', $thisWeek)->count();
        $pendingTasks = 0;
        $totalNotifications = 0;
        $openTasks = 0;
        $completedTasks = 0;
        $overdueTasks = 0;

        $activityLabels = [];
        $activeData = [];
        $idleData = [];
        $inactiveData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $activityLabels[] = $date->format('M d');
            $activeData[] = User::whereDate('updated_at', $date->toDateString())->count();
            $idleData[] = 0;
            $inactiveData[] = 0;
        }

        $roleLabels = ['Admin', 'User'];
        $roleCounts = [
            User::where('email', 'like', '%admin%')->count(),
            User::where('email', 'not like', '%admin%')->count(),
        ];

        $recentUsers = User::orderBy('created_at', 'desc')->take(6)->get();

        return view('home', compact(
            'totalUsers', 'activeToday', 'newUsers', 'pendingTasks', 'totalNotifications',
            'openTasks', 'completedTasks', 'overdueTasks',
            'activityLabels', 'activeData', 'idleData', 'inactiveData',
            'roleLabels', 'roleCounts',
            'recentUsers'
        ));
    }
}

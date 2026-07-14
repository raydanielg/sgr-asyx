@extends('layouts.dashboard')

@section('title', 'Dashboard - SGR')
@section('page_title', 'Dashboard')

@section('content')

{{-- Welcome --}}
<div class="mb-6 flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Hello {{ Auth::user()->name ?? 'Admin' }}</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Here's your SGR overview for {{ now()->format('M d, Y') }}.</p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <a href="#" class="px-2 sm:px-3 py-1.5 text-[11px] sm:text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="hidden sm:inline">Reports</span>
        </a>
        <a href="#" class="px-2 sm:px-3 py-1.5 text-[11px] sm:text-xs font-medium bg-maroon-500 text-white rounded-lg hover:bg-maroon-600 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">New Record</span>
        </a>
    </div>
</div>

{{-- KPI Stat Cards --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4 mb-6">
    {{-- Total Users --}}
    <div class="card-sm bg-gradient-to-br from-maroon-600 to-maroon-700 rounded-xl border border-maroon-500 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-maroon-100">Total Users</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-maroon-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($totalUsers ?? 0) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-maroon-200 font-medium relative z-10">Registered users</div>
    </div>

    {{-- Active Today --}}
    <div class="card-sm bg-gradient-to-br from-info-500 to-info-600 rounded-xl border border-info-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-info-100">Active Today</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-info-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $activeToday ?? 0 }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-info-100 font-medium relative z-10">Logins today</div>
    </div>

    {{-- Pending Tasks --}}
    <div class="card-sm bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl border border-orange-300 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-orange-50">Pending Tasks</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-orange-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($pendingTasks ?? 0) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-orange-50 font-medium relative z-10">Awaiting action</div>
    </div>

    {{-- Notifications --}}
    <div class="card-sm bg-gradient-to-br from-success-500 to-success-600 rounded-xl border border-success-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-success-100">Notifications</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-success-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $totalNotifications ?? 0 }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-success-100 font-medium relative z-10">Unread alerts</div>
    </div>
</div>

{{-- Activity Overview --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- Activity Trend (30 days) --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Activity Trend</h3>
                <p class="text-xs text-gray-400">Last 30 days</p>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-maroon-500"></span>Active</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-warning-400"></span>Idle</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-danger-400"></span>Inactive</span>
            </div>
        </div>
        @php
            $actMax = max(array_merge($activeData ?? [1], $idleData ?? [0], $inactiveData ?? [0], [1]));
            $actCount = count($activityLabels ?? []);
        @endphp
        <div class="flex items-end gap-[1px] h-48">
            @for($i = 0; $i < $actCount; $i++)
                @php
                    $aPct = (($activeData[$i] ?? 0) / $actMax) * 100;
                    $iPct = (($idleData[$i] ?? 0) / $actMax) * 100;
                    $inPct = (($inactiveData[$i] ?? 0) / $actMax) * 100;
                    $totalPct = $aPct + $iPct + $inPct;
                @endphp
                <div class="flex-1 flex flex-col items-center group cursor-pointer relative" style="min-width: 4px;">
                    <div class="w-full bg-gray-50 rounded-t-sm relative h-44 overflow-hidden flex flex-col justify-end">
                        @if($totalPct > 0)
                            <div class="w-full bg-danger-400" style="height: {{ max($inPct, 0) }}%"></div>
                            <div class="w-full bg-warning-400" style="height: {{ max($iPct, 0) }}%"></div>
                            <div class="w-full bg-maroon-500" style="height: {{ max($aPct, 0) }}%"></div>
                        @endif
                    </div>
                    @if($i % 5 === 0)
                        <span class="text-[8px] text-gray-400 font-medium mt-1 whitespace-nowrap">{{ $activityLabels[$i] }}</span>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- User Distribution --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-900">User Distribution</h3>
            <p class="text-xs text-gray-400">By role</p>
        </div>
        @php $roleMax = max($roleCounts ?: [1]); @endphp
        <div class="space-y-3">
            @forelse($roleLabels as $i => $label)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-gray-700 truncate">{{ $label }}</span>
                        <span class="text-xs font-bold text-gray-900">{{ $roleCounts[$i] }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-maroon-500 to-maroon-600 rounded-full transition-all duration-500" style="width: {{ ($roleCounts[$i] / $roleMax) * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-8">No data yet</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="bg-white rounded-xl border p-5 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <h3 class="text-sm font-semibold text-gray-900">Action Center</h3>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-maroon-200 hover:bg-maroon-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">New Users</span>
                <span class="text-lg font-bold text-maroon-500">{{ $newUsers ?? 0 }}</span>
            </div>
            <span class="text-[10px] text-gray-400">This week</span>
        </div>
        <div class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-maroon-200 hover:bg-maroon-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Open Tasks</span>
                <span class="text-lg font-bold {{ ($openTasks ?? 0) > 0 ? 'text-warning-500' : 'text-gray-300' }}">{{ $openTasks ?? 0 }}</span>
            </div>
            <span class="text-[10px] text-gray-400">In progress</span>
        </div>
        <div class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-maroon-200 hover:bg-maroon-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Completed</span>
                <span class="text-lg font-bold {{ ($completedTasks ?? 0) > 0 ? 'text-orange-500' : 'text-gray-300' }}">{{ $completedTasks ?? 0 }}</span>
            </div>
            <span class="text-[10px] text-gray-400">This month</span>
        </div>
        <div class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-maroon-200 hover:bg-maroon-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Overdue</span>
                <span class="text-lg font-bold {{ ($overdueTasks ?? 0) > 0 ? 'text-danger-500' : 'text-gray-300' }}">{{ $overdueTasks ?? 0 }}</span>
            </div>
            <span class="text-[10px] text-gray-400">Past deadline</span>
        </div>
    </div>
</div>

{{-- Recent Activity + System Info --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- System Performance --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-900">System Health</h3>
            <p class="text-xs text-gray-400">Current status</p>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 rounded-lg bg-success-50 border border-success-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-gray-700">Server Status</span>
                </div>
                <span class="text-xs font-bold text-success-600">Online</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-maroon-50 border border-maroon-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-maroon-500"></span>
                    <span class="text-xs font-medium text-gray-700">Database</span>
                </div>
                <span class="text-xs font-bold text-maroon-600">Connected</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-orange-50 border border-orange-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <span class="text-xs font-medium text-gray-700">Cache</span>
                </div>
                <span class="text-xs font-bold text-orange-600">Active</span>
            </div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Users</h3>
                <p class="text-xs text-gray-400">Latest registered users</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                        <th class="px-3 py-2 font-medium">Name</th>
                        <th class="px-3 py-2 font-medium">Email</th>
                        <th class="px-3 py-2 font-medium">Joined</th>
                        <th class="px-3 py-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers ?? [] as $user)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-[10px]">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-2.5 text-gray-500">{{ $user->email ?? 'N/A' }}</td>
                        <td class="px-3 py-2.5 text-gray-500">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="px-3 py-2.5">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-success-50 text-success-700 border border-success-100">Verified</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-warning-50 text-warning-700 border border-warning-100">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-3 py-8 text-center text-gray-400">
                            <p class="text-sm">No users yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="h-16 lg:hidden"></div>

@endsection

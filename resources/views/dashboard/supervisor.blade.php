@extends('layouts.dashboard')

@section('page_title', 'Supervisor Dashboard - SGR')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Hello, {{ Auth::user()->name }}</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Your station overview — {{ now()->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('reports.create') }}" class="px-3 py-1.5 text-xs font-medium bg-orange-400 text-white rounded-lg hover:bg-orange-500 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Submit Report
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
        <div class="card-sm bg-gradient-to-br from-maroon-600 to-maroon-700 rounded-xl border border-maroon-500 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-maroon-100">Today's Reports</span>
                <svg class="w-4 h-4 text-maroon-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">{{ $todayReports }}</div>
            <div class="mt-1 text-[10px] sm:text-xs text-maroon-200 font-medium relative z-10">Submitted today</div>
        </div>

        <div class="card-sm bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl border border-orange-300 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-orange-50">Month Revenue</span>
                <svg class="w-4 h-4 text-orange-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">TZS {{ number_format($monthRevenue / 1000000, 1) }}M</div>
            <div class="mt-1 text-[10px] sm:text-xs text-orange-50 font-medium relative z-10">This month</div>
        </div>

        <div class="card-sm bg-gradient-to-br from-warning-400 to-warning-500 rounded-xl border border-warning-300 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-warning-50">Pending</span>
                <svg class="w-4 h-4 text-warning-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">{{ $pendingReports }}</div>
            <div class="mt-1 text-[10px] sm:text-xs text-warning-50 font-medium relative z-10">Awaiting approval</div>
        </div>

        <div class="card-sm bg-gradient-to-br from-success-500 to-success-600 rounded-xl border border-success-400 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-success-100">Approved</span>
                <svg class="w-4 h-4 text-success-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">{{ $approvedReports }}</div>
            <div class="mt-1 text-[10px] sm:text-xs text-success-100 font-medium relative z-10">Approved reports</div>
        </div>
    </div>

    {{-- Target Progress --}}
    @if($monthlyTarget)
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Monthly Target Progress</h3>
                <p class="text-xs text-gray-400">{{ ucfirst($monthlyTarget->target_type) }} — {{ ucfirst(str_replace('_', ' ', $monthlyTarget->metric)) }}</p>
            </div>
        </div>
        @php
            $achieved = $monthRevenue;
            $pct = $monthlyTarget->target_value > 0 ? min(($achieved / $monthlyTarget->target_value) * 100, 100) : 0;
            $remaining = max($monthlyTarget->target_value - $achieved, 0);
        @endphp
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-gray-600">TZS {{ number_format($achieved) }} / {{ number_format($monthlyTarget->target_value) }}</span>
            <span class="text-xs font-bold {{ $pct >= 100 ? 'text-success-600' : ($pct >= 50 ? 'text-warning-600' : 'text-danger-600') }}">{{ number_format($pct, 1) }}%</span>
        </div>
        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-maroon-500 to-orange-400 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">Remaining: TZS {{ number_format($remaining) }}</p>
    </div>
    @endif

    {{-- My Stations --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">My Assigned Stations</h3>
        @if($stations->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($stations as $station)
            <div class="border border-gray-100 rounded-xl p-4 hover:border-maroon-200 hover:bg-maroon-50/30 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-900">{{ $station->name }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $station->status === 'active' ? 'bg-success-50 text-success-700 border border-success-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }}">{{ ucfirst($station->status) }}</span>
                </div>
                <p class="text-xs text-gray-500">{{ $station->parkingLot->name ?? 'N/A' }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $station->booths->count() }} booth(s)</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-xs text-gray-400 text-center py-6">No stations assigned to you yet.</p>
        @endif
    </div>

    {{-- Recent Reports --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Reports</h3>
                <p class="text-xs text-gray-400">Your latest submissions</p>
            </div>
            <a href="{{ route('reports.index') }}" class="text-xs font-medium text-maroon-600 hover:text-maroon-700">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide border-b border-gray-100">
                        <th class="pb-2 font-medium">Station</th>
                        <th class="pb-2 font-medium">Date</th>
                        <th class="pb-2 font-medium">Collected</th>
                        <th class="pb-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $report)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="py-2.5 text-sm text-gray-900 font-medium">{{ $report->station->name ?? 'N/A' }}</td>
                        <td class="py-2.5 text-sm text-gray-500">{{ $report->report_date->format('M d, Y') }}</td>
                        <td class="py-2.5 text-sm text-gray-900 font-medium">TZS {{ number_format($report->total_collected) }}</td>
                        <td class="py-2.5">
                            @if($report->status === 'approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-success-50 text-success-700 border border-success-100">Approved</span>
                            @elseif($report->status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-warning-50 text-warning-700 border border-warning-100">Pending</span>
                            @elseif($report->status === 'rejected')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-danger-50 text-danger-700 border border-danger-100">Rejected</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-700 border border-gray-100">Draft</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-gray-400 text-sm">No reports submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

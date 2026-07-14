@extends('layouts.dashboard')

@section('page_title', 'Dashboard - SGR')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Welcome, {{ Auth::user()->name }}</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">SGR Parking Revenue Overview — {{ now()->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('reports.index') }}" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="hidden sm:inline">Reports</span>
            </a>
            @if(auth()->user()->isAdminManager())
            <a href="{{ route('parking-lots.create') }}" class="px-3 py-1.5 text-xs font-medium bg-maroon-500 text-white rounded-lg hover:bg-maroon-600 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline">New Lot</span>
            </a>
            @endif
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
        <div class="card-sm bg-gradient-to-br from-maroon-600 to-maroon-700 rounded-xl border border-maroon-500 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-maroon-100">Parking Lots</span>
                <svg class="w-4 h-4 text-maroon-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">{{ $totalLots }}</div>
            <div class="mt-1 text-[10px] sm:text-xs text-maroon-200 font-medium relative z-10">Registered lots</div>
        </div>

        <div class="card-sm bg-gradient-to-br from-info-500 to-info-600 rounded-xl border border-info-400 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-info-100">Stations</span>
                <svg class="w-4 h-4 text-info-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">{{ $totalStations }}</div>
            <div class="mt-1 text-[10px] sm:text-xs text-info-100 font-medium relative z-10">Active stations</div>
        </div>

        <div class="card-sm bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl border border-orange-300 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-orange-50">Month Revenue</span>
                <svg class="w-4 h-4 text-orange-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">TZS {{ number_format($monthRevenue / 1000000, 1) }}M</div>
            <div class="mt-1 text-[10px] sm:text-xs text-orange-50 font-medium relative z-10">Collected this month</div>
        </div>

        <div class="card-sm bg-gradient-to-br from-success-500 to-success-600 rounded-xl border border-success-400 p-3 sm:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="flex items-start justify-between relative z-10">
                <span class="text-[10px] sm:text-xs font-medium text-success-100">Supervisors</span>
                <svg class="w-4 h-4 text-success-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
            </div>
            <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight relative z-10">{{ $activeSupervisors }}</div>
            <div class="mt-1 text-[10px] sm:text-xs text-success-100 font-medium relative z-10">Active supervisors</div>
        </div>
    </div>

    {{-- Revenue Summary Cards --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Today</p>
            <p class="mt-1 text-lg font-bold text-gray-900">TZS {{ number_format($todayRevenue) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">This Week</p>
            <p class="mt-1 text-lg font-bold text-gray-900">TZS {{ number_format($weekRevenue) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">This Year</p>
            <p class="mt-1 text-lg font-bold text-gray-900">TZS {{ number_format($yearRevenue) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Pending Approvals</p>
            <p class="mt-1 text-lg font-bold {{ $pendingApprovals > 0 ? 'text-warning-500' : 'text-gray-900' }}">{{ $pendingApprovals }}</p>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Revenue Performance (30 Days)</h3>
                <p class="text-xs text-gray-400">Collected vs Deposited</p>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-maroon-500"></span>Collected</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-400"></span>Deposited</span>
            </div>
        </div>
        <div class="flex items-end gap-[1px] h-48">
            @php $revMax = max(array_merge($revenueCollected, $revenueDeposited, [1])); @endphp
            @for($i = 0; $i < count($revenueLabels); $i++)
                <div class="flex-1 flex flex-col items-center group cursor-pointer relative" style="min-width: 4px;">
                    <div class="w-full bg-gray-50 rounded-t-sm relative h-44 overflow-hidden flex flex-col justify-end">
                        @if($revenueDeposited[$i] > 0)
                            <div class="w-full bg-orange-400" style="height: {{ ($revenueDeposited[$i] / $revMax) * 100 }}%"></div>
                        @endif
                        @if($revenueCollected[$i] > 0)
                            <div class="w-full bg-maroon-500 absolute bottom-0" style="height: {{ ($revenueCollected[$i] / $revMax) * 100 }}%; opacity: 0.6;"></div>
                        @endif
                    </div>
                    @if($i % 5 === 0)
                        <span class="text-[8px] text-gray-400 font-medium mt-1 whitespace-nowrap">{{ $revenueLabels[$i] }}</span>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- Targets + Variance --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Targets --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Target Achievement</h3>
            <div class="space-y-4">
                @if($contractualTarget)
                    @php $contractAchieved = $monthRevenue; $contractPct = $contractualTarget->target_value > 0 ? min(($contractAchieved / $contractualTarget->target_value) * 100, 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium text-gray-700">Contractual Target</span>
                            <span class="text-xs font-bold {{ $contractPct >= 100 ? 'text-success-600' : ($contractPct >= 50 ? 'text-warning-600' : 'text-danger-600') }}">{{ number_format($contractPct, 1) }}%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-maroon-500 to-maroon-600 rounded-full" style="width: {{ $contractPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">TZS {{ number_format($contractAchieved) }} / {{ number_format($contractualTarget->target_value) }}</p>
                    </div>
                @else
                    <p class="text-xs text-gray-400">No contractual target set for this period.</p>
                @endif

                @if($companyTarget)
                    @php $companyAchieved = $monthRevenue; $companyPct = $companyTarget->target_value > 0 ? min(($companyAchieved / $companyTarget->target_value) * 100, 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium text-gray-700">Company Set Target</span>
                            <span class="text-xs font-bold {{ $companyPct >= 100 ? 'text-success-600' : ($companyPct >= 50 ? 'text-warning-600' : 'text-danger-600') }}">{{ number_format($companyPct, 1) }}%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-orange-500 rounded-full" style="width: {{ $companyPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">TZS {{ number_format($companyAchieved) }} / {{ number_format($companyTarget->target_value) }}</p>
                    </div>
                @else
                    <p class="text-xs text-gray-400">No company-set target for this period.</p>
                @endif
            </div>
        </div>

        {{-- Variance Alerts --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <h3 class="text-sm font-semibold text-gray-900">Variance Alerts</h3>
                @if($variances->count() > 0)
                    <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-danger-50 text-danger-600 border border-danger-100">{{ $variances->count() }}</span>
                @endif
            </div>
            @if($variances->count() > 0)
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($variances as $variance)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $variance->difference < 0 ? 'bg-danger-50 border border-danger-100' : 'bg-warning-50 border border-warning-100' }}">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-900 truncate">{{ $variance->cashier_name ?? 'Unknown' }}</p>
                                <p class="text-[10px] text-gray-500">{{ $variance->dailyReport->station->name ?? 'N/A' }} — {{ $variance->date_in->format('M d') }}</p>
                            </div>
                            <span class="text-xs font-bold {{ $variance->difference < 0 ? 'text-danger-600' : 'text-warning-600' }}">{{ number_format($variance->difference) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-10 h-10 text-success-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-gray-400">No variances detected this month.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Reports --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Reports</h3>
                <p class="text-xs text-gray-400">Latest submitted daily reports</p>
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
                        <th class="pb-2 font-medium">Submitted By</th>
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
                        <td class="py-2.5 text-sm text-gray-500">{{ $report->submittedBy->name ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-400 text-sm">No reports yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

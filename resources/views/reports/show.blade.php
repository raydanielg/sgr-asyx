@extends('layouts.dashboard')

@section('page_title', 'Report Details - SGR')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('reports.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-900">Report — {{ $report->report_date->format('M d, Y') }}</h1>
            <p class="text-sm text-gray-500">{{ $report->station->name ?? 'N/A' }}</p>
        </div>
        @if($report->status !== 'approved' && (auth()->user()->isSupervisor() || auth()->user()->isAdminManager()))
        <a href="{{ route('reports.edit', $report) }}" class="px-3 py-2 text-xs font-medium bg-maroon-500 text-white rounded-lg hover:bg-maroon-600 transition-colors">Edit</a>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Collected</p>
            <p class="mt-1 text-sm font-bold text-gray-900">TZS {{ number_format($report->total_collected) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Deposited</p>
            <p class="mt-1 text-sm font-bold text-gray-900">TZS {{ number_format($report->total_deposited) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Difference</p>
            <p class="mt-1 text-sm font-bold {{ $report->total_difference != 0 ? 'text-danger-600' : 'text-success-600' }}">TZS {{ number_format($report->total_difference) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Status</p>
            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium
                {{ $report->status === 'approved' ? 'bg-success-50 text-success-700 border border-success-100' :
                   ($report->status === 'pending' ? 'bg-warning-50 text-warning-700 border border-warning-100' :
                   ($report->status === 'rejected' ? 'bg-danger-50 text-danger-700 border border-danger-100' :
                   'bg-gray-50 text-gray-700 border border-gray-100')) }}">{{ ucfirst($report->status) }}</span>
        </div>
    </div>

    {{-- Review info --}}
    @if($report->reviewed_by)
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span>Reviewed by: <strong class="text-gray-900">{{ $report->reviewedBy->name ?? 'N/A' }}</strong></span>
            <span>At: <strong class="text-gray-900">{{ $report->reviewed_at?->format('M d, Y H:i') }}</strong></span>
        </div>
        @if($report->review_notes)
        <p class="mt-2 text-xs text-gray-600">{{ $report->review_notes }}</p>
        @endif
    </div>
    @endif

    {{-- Collections Table --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Collection Entries ({{ $report->collections->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide border-b border-gray-100">
                        <th class="pb-2 font-medium">Booth</th>
                        <th class="pb-2 font-medium">Shift</th>
                        <th class="pb-2 font-medium">Date In</th>
                        <th class="pb-2 font-medium">Collected</th>
                        <th class="pb-2 font-medium">Deposited</th>
                        <th class="pb-2 font-medium">Diff</th>
                        <th class="pb-2 font-medium">Control No.</th>
                        <th class="pb-2 font-medium">Receipt No.</th>
                        <th class="pb-2 font-medium">Cashier</th>
                        <th class="pb-2 font-medium">Flags</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report->collections as $col)
                    <tr class="border-t border-gray-100">
                        <td class="py-2.5 text-sm text-gray-900">{{ $col->booth->name ?? 'N/A' }}</td>
                        <td class="py-2.5 text-xs text-gray-500">{{ ucfirst($col->shift) }}</td>
                        <td class="py-2.5 text-xs text-gray-500">{{ $col->date_in->format('M d') }}</td>
                        <td class="py-2.5 text-sm font-medium text-gray-900">{{ number_format($col->amount_collected) }}</td>
                        <td class="py-2.5 text-sm text-gray-500">{{ number_format($col->amount_deposited) }}</td>
                        <td class="py-2.5 text-sm font-medium {{ $col->difference != 0 ? 'text-danger-600' : 'text-success-600' }}">{{ number_format($col->difference) }}</td>
                        <td class="py-2.5 text-xs text-gray-500">{{ $col->control_no ?? '—' }}</td>
                        <td class="py-2.5 text-xs text-gray-500">{{ $col->receipt_no ?? '—' }}</td>
                        <td class="py-2.5 text-xs text-gray-500">{{ $col->cashier_name ?? '—' }}</td>
                        <td class="py-2.5">
                            @if($col->flags && count($col->flags) > 0)
                                @foreach($col->flags as $flag)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium {{ $flag === 'variance' ? 'bg-danger-50 text-danger-600' : 'bg-warning-50 text-warning-600' }}">{{ str_replace('_', ' ', $flag) }}</span>
                                @endforeach
                            @else
                                <span class="text-[10px] text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($report->comments)
    <div class="bg-white rounded-xl border p-4">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1">Comments / Challenges</p>
        <p class="text-sm text-gray-700">{{ $report->comments }}</p>
    </div>
    @endif

    {{-- Approval Actions --}}
    @if($report->status === 'pending' && auth()->user()->hasRole(['owner', 'admin_manager']))
    <div class="bg-white rounded-xl border p-5 space-y-3">
        <h3 class="text-sm font-semibold text-gray-900">Review Actions</h3>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('reports.approve', $report) }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="review_notes" value="Approved.">
                <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-success-500 rounded-lg hover:bg-success-600 transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Approve Report
                </button>
            </form>
            <form action="{{ route('reports.reject', $report) }}" method="POST" class="flex-1 space-y-2">
                @csrf
                <input type="text" name="review_notes" required placeholder="Reason for rejection..." class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-danger-500 focus:ring-2 focus:ring-danger-200 outline-none text-sm">
                <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-danger-500 rounded-lg hover:bg-danger-600 transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reject Report
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

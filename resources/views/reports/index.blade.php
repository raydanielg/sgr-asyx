@extends('layouts.dashboard')

@section('page_title', 'Daily Reports - SGR')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Daily Reports</h1>
            <p class="text-sm text-gray-500">@if(auth()->user()->isSupervisor()) Your submitted reports @else All submitted reports @endif</p>
        </div>
        @if(auth()->user()->isSupervisor() || auth()->user()->isAdminManager())
        <a href="{{ route('reports.create') }}" class="px-3 py-2 text-xs font-medium bg-orange-400 text-white rounded-lg hover:bg-orange-500 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Submit Report
        </a>
        @endif
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Station</th>
                        <th class="px-4 py-3 font-medium">Date</th>
                        <th class="px-4 py-3 font-medium">Collected</th>
                        <th class="px-4 py-3 font-medium">Deposited</th>
                        <th class="px-4 py-3 font-medium">Diff</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Source</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $report->station->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $report->report_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">TZS {{ number_format($report->total_collected) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">TZS {{ number_format($report->total_deposited) }}</td>
                        <td class="px-4 py-3 text-sm font-medium {{ $report->total_difference != 0 ? 'text-danger-600' : 'text-success-600' }}">{{ number_format($report->total_difference) }}</td>
                        <td class="px-4 py-3">
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
                        <td class="px-4 py-3 text-xs text-gray-500">{{ ucfirst($report->source) }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('reports.show', $report) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($report->status !== 'approved' && (auth()->user()->isSupervisor() || auth()->user()->isAdminManager()))
                                <a href="{{ route('reports.edit', $report) }}" class="p-1.5 rounded-lg hover:bg-maroon-50 text-maroon-600" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif
                                @if($report->status === 'pending' && auth()->user()->hasRole(['owner', 'admin_manager']))
                                <form action="{{ route('reports.approve', $report) }}" method="POST" data-confirm="Approve this report?">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-success-50 text-success-600" title="Approve">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif
                                @if($report->status !== 'approved')
                                <form action="{{ route('reports.destroy', $report) }}" method="POST" data-confirm="Delete this report?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-danger-50 text-danger-600" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No reports yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $reports->links() }}
</div>
@endsection

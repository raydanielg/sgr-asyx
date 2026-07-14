@extends('layouts.dashboard')

@section('page_title', 'Audit Logs - SGR')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Audit Logs</h1>
        <p class="text-sm text-gray-500">System activity trail</p>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Timestamp</th>
                        <th class="px-4 py-3 font-medium">User</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                        <th class="px-4 py-3 font-medium">Entity</th>
                        <th class="px-4 py-3 font-medium">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $log->action === 'create' ? 'bg-success-50 text-success-700 border border-success-100' : ($log->action === 'delete' ? 'bg-danger-50 text-danger-700 border border-danger-100' : ($log->action === 'approve' ? 'bg-info-50 text-info-700 border border-info-100' : ($log->action === 'reject' ? 'bg-danger-50 text-danger-700 border border-danger-100' : 'bg-gray-50 text-gray-700 border border-gray-100'))) }}">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $log->entity ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $log->details ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400 text-sm">No audit logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $logs->links() }}
</div>
@endsection

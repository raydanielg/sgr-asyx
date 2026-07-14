@extends('layouts.dashboard')

@section('page_title', 'Targets - SGR')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Targets</h1>
            <p class="text-sm text-gray-500">Manage contractual and company-set targets</p>
        </div>
        <a href="{{ route('targets.create') }}" class="px-3 py-2 text-xs font-medium bg-orange-400 text-white rounded-lg hover:bg-orange-500 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Target
        </a>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Type</th>
                        <th class="px-4 py-3 font-medium">Scope</th>
                        <th class="px-4 py-3 font-medium">Metric</th>
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">Target Value</th>
                        <th class="px-4 py-3 font-medium">Validity</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($targets as $target)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $target->target_type === 'contractual' ? 'bg-maroon-50 text-maroon-700 border border-maroon-100' : 'bg-orange-50 text-orange-700 border border-orange-100' }}">{{ ucfirst(str_replace('_', ' ', $target->target_type)) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ ucfirst($target->scope) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ str_replace('_', ' ', $target->metric) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ ucfirst($target->period) }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ number_format($target->target_value) }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $target->start_date->format('M d, Y') }} — {{ $target->end_date?->format('M d, Y') ?? 'Ongoing' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('targets.edit', $target) }}" class="p-1.5 rounded-lg hover:bg-maroon-50 text-maroon-600" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('targets.destroy', $target) }}" method="POST" data-confirm="Delete this target?" data-confirm-text="Yes, delete it">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-danger-50 text-danger-600" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">No targets yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $targets->links() }}
</div>
@endsection

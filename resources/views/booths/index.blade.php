@extends('layouts.dashboard')

@section('page_title', 'Booths - SGR')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Booths</h1>
            <p class="text-sm text-gray-500">Manage all booth definitions</p>
        </div>
        <a href="{{ route('booths.create') }}" class="px-3 py-2 text-xs font-medium bg-orange-400 text-white rounded-lg hover:bg-orange-500 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Booth
        </a>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Station</th>
                        <th class="px-4 py-3 font-medium">Parking Lot</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($booths as $booth)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $booth->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $booth->station->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $booth->station->parkingLot->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('booths.edit', $booth) }}" class="p-1.5 rounded-lg hover:bg-maroon-50 text-maroon-600" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('booths.destroy', $booth) }}" method="POST" data-confirm="Delete this booth?" data-confirm-text="Yes, delete it">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-danger-50 text-danger-600" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400 text-sm">No booths yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $booths->links() }}
</div>
@endsection

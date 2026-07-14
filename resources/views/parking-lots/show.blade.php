@extends('layouts.dashboard')

@section('page_title', 'Parking Lot Details - SGR')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('parking-lots.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-900">{{ $parkingLot->name }}</h1>
            <p class="text-sm text-gray-500">{{ $parkingLot->location }}</p>
        </div>
        <a href="{{ route('parking-lots.edit', $parkingLot) }}" class="px-3 py-2 text-xs font-medium bg-maroon-500 text-white rounded-lg hover:bg-maroon-600 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Address</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ $parkingLot->address ?: 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Capacity</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ $parkingLot->capacity }} vehicles</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Status</p>
            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $parkingLot->status === 'active' ? 'bg-success-50 text-success-700 border border-success-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }}">{{ ucfirst($parkingLot->status) }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Stations ({{ $parkingLot->stations->count() }})</h3>
        @if($parkingLot->stations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] text-gray-500 uppercase tracking-wide border-b border-gray-100">
                        <th class="pb-2 font-medium">Name</th>
                        <th class="pb-2 font-medium">Supervisor</th>
                        <th class="pb-2 font-medium">Booths</th>
                        <th class="pb-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parkingLot->stations as $station)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50">
                        <td class="py-2.5 text-sm font-medium text-gray-900">{{ $station->name }}</td>
                        <td class="py-2.5 text-sm text-gray-500">{{ $station->supervisor->name ?? 'Unassigned' }}</td>
                        <td class="py-2.5 text-sm text-gray-500">{{ $station->booths->count() }}</td>
                        <td class="py-2.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $station->status === 'active' ? 'bg-success-50 text-success-700 border border-success-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }}">{{ ucfirst($station->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-xs text-gray-400 text-center py-6">No stations registered under this lot yet.</p>
        @endif
    </div>
</div>
@endsection

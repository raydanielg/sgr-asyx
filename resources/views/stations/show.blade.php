@extends('layouts.dashboard')

@section('page_title', 'Station Details - SGR')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('stations.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-900">{{ $station->name }}</h1>
            <p class="text-sm text-gray-500">{{ $station->parkingLot->name ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('stations.edit', $station) }}" class="px-3 py-2 text-xs font-medium bg-maroon-500 text-white rounded-lg hover:bg-maroon-600 transition-colors">Edit</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Supervisor</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ $station->supervisor->name ?? 'Unassigned' }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Booths</p>
            <p class="mt-1 text-sm font-medium text-gray-900">{{ $station->booths->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Status</p>
            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $station->status === 'active' ? 'bg-success-50 text-success-700 border border-success-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }}">{{ ucfirst($station->status) }}</span>
        </div>
    </div>

    @if($station->notes)
    <div class="bg-white rounded-xl border p-4">
        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1">Notes</p>
        <p class="text-sm text-gray-700">{{ $station->notes }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Booths ({{ $station->booths->count() }})</h3>
        @if($station->booths->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($station->booths as $booth)
            <div class="border border-gray-100 rounded-xl p-3">
                <p class="text-sm font-medium text-gray-900">{{ $booth->name }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-xs text-gray-400 text-center py-6">No booths registered yet.</p>
        @endif
    </div>
</div>
@endsection

@extends('layouts.dashboard')

@section('page_title', 'New Booth - SGR')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('booths.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">New Booth</h1>
            <p class="text-sm text-gray-500">Register a new booth</p>
        </div>
    </div>

    <form action="{{ route('booths.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Station <span class="text-danger-500">*</span></label>
                <select name="station_id" required class="w-full px-4 py-2.5 rounded-lg border @error('station_id') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="">Select a station</option>
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>{{ $station->parkingLot->name }} — {{ $station->name }}</option>
                    @endforeach
                </select>
                @error('station_id')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name <span class="text-danger-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border @error('name') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm" placeholder="e.g., Booth 1 Main">
                @error('name')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('booths.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-maroon-500 rounded-lg hover:bg-maroon-600 transition-colors">Create Booth</button>
        </div>
    </form>
</div>
@endsection

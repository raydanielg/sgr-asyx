@extends('layouts.dashboard')

@section('page_title', 'New Station - SGR')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('stations.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">New Station</h1>
            <p class="text-sm text-gray-500">Register a new station/booth</p>
        </div>
    </div>

    <form action="{{ route('stations.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Parking Lot <span class="text-danger-500">*</span></label>
                <select name="parking_lot_id" required class="w-full px-4 py-2.5 rounded-lg border @error('parking_lot_id') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="">Select a lot</option>
                    @foreach($lots as $lot)
                    <option value="{{ $lot->id }}" {{ old('parking_lot_id') == $lot->id ? 'selected' : '' }}>{{ $lot->name }}</option>
                    @endforeach
                </select>
                @error('parking_lot_id')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Supervisor</label>
                <select name="supervisor_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="">Unassigned</option>
                    @foreach($supervisors as $sup)
                    <option value="{{ $sup->id }}" {{ old('supervisor_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name <span class="text-danger-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border @error('name') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm" placeholder="e.g., Booth 1 (Main)">
                @error('name')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm" placeholder="Additional notes (optional)">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('stations.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-maroon-500 rounded-lg hover:bg-maroon-600 transition-colors">Create Station</button>
        </div>
    </form>
</div>
@endsection

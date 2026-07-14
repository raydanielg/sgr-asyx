@extends('layouts.dashboard')

@section('page_title', 'New Target - SGR')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('targets.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">New Target</h1>
            <p class="text-sm text-gray-500">Set a contractual or company-set target</p>
        </div>
    </div>

    <form action="{{ route('targets.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scope <span class="text-danger-500">*</span></label>
                <select name="scope" id="targetScope" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="company" {{ old('scope') === 'company' ? 'selected' : '' }}>Company-wide</option>
                    <option value="parking_lot" {{ old('scope') === 'parking_lot' ? 'selected' : '' }}>Parking Lot</option>
                    <option value="station" {{ old('scope') === 'station' ? 'selected' : '' }}>Station</option>
                </select>
            </div>
            <div id="scopeIdContainer" style="display:none;">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scope Target</label>
                <select name="scope_id" id="scopeIdSelect" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="">Select...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Target Type <span class="text-danger-500">*</span></label>
                <select name="target_type" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="contractual" {{ old('target_type') === 'contractual' ? 'selected' : '' }}>Contractual Set Target</option>
                    <option value="company_set" {{ old('target_type') === 'company_set' ? 'selected' : '' }}>Company Set Target</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Metric <span class="text-danger-500">*</span></label>
                <select name="metric" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="revenue_tzs" {{ old('metric') === 'revenue_tzs' ? 'selected' : '' }}>Revenue (TZS)</option>
                    <option value="vehicle_count" {{ old('metric') === 'vehicle_count' ? 'selected' : '' }}>Vehicle Count</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Period <span class="text-danger-500">*</span></label>
                <select name="period" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="daily" {{ old('period') === 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ old('period') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ old('period') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('period') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Target Value <span class="text-danger-500">*</span></label>
                <input type="number" name="target_value" value="{{ old('target_value') }}" required min="0" step="0.01" class="w-full px-4 py-2.5 rounded-lg border @error('target_value') border-danger-300 @else border-gray-200 @enderror focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm" placeholder="e.g., 10000000">
                @error('target_value')<p class="mt-1 text-xs text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Start Date <span class="text-danger-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('targets.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-maroon-500 rounded-lg hover:bg-maroon-600 transition-colors">Create Target</button>
        </div>
    </form>
</div>

<script>
const lots = @json($lots->map(fn($l) => ['id' => $l->id, 'name' => $l->name]));
const stations = @json($stations->map(fn($s) => ['id' => $s->id, 'name' => $s->parkingLot->name . ' — ' . $s->name]));
const scopeSelect = document.getElementById('targetScope');
const container = document.getElementById('scopeIdContainer');
const select = document.getElementById('scopeIdSelect');

scopeSelect.addEventListener('change', function() {
    if (this.value === 'company') {
        container.style.display = 'none';
    } else {
        container.style.display = '';
        const data = this.value === 'parking_lot' ? lots : stations;
        select.innerHTML = '<option value="">Select...</option>' + data.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
    }
});
</script>
@endsection

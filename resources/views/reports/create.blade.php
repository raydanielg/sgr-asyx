@extends('layouts.dashboard')

@section('page_title', 'Submit Daily Report - SGR')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('reports.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Submit Daily Report</h1>
            <p class="text-sm text-gray-500">Enter collection data for a station</p>
        </div>
    </div>

    <form id="reportForm" action="{{ route('reports.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-5">
        @csrf
        {{-- Report header --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Station <span class="text-danger-500">*</span></label>
                <select name="station_id" id="stationSelect" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
                    <option value="">Select a station</option>
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" data-booths="{{ json_encode($station->booths->map(fn($b) => ['id' => $b->id, 'name' => $b->name])) }}">{{ $station->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Report Date <span class="text-danger-500">*</span></label>
                <input type="date" name="report_date" value="{{ old('report_date', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Comments / Challenges</label>
                <textarea name="comments" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none transition-all text-sm" placeholder="Any challenges or notes (optional)">{{ old('comments') }}</textarea>
            </div>
        </div>

        {{-- Collections --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900">Collection Entries</h3>
                <button type="button" id="addCollection" class="px-3 py-1.5 text-xs font-medium bg-maroon-50 text-maroon-700 border border-maroon-100 rounded-lg hover:bg-maroon-100 transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Entry
                </button>
            </div>
            <div id="collectionsContainer" class="space-y-3"></div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('reports.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-orange-400 rounded-lg hover:bg-orange-500 transition-colors">Submit Report</button>
        </div>
    </form>
</div>

<script>
let collectionIndex = 0;
const boothTemplate = '<option value="">No specific booth</option>';

function getBoothOptions() {
    const select = document.getElementById('stationSelect');
    const option = select.options[select.selectedIndex];
    if (!option || !option.dataset.booths) return boothTemplate;
    const booths = JSON.parse(option.dataset.booths);
    return boothTemplate + booths.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
}

function addCollection(data = {}) {
    const idx = collectionIndex++;
    const div = document.createElement('div');
    div.className = 'border border-gray-100 rounded-xl p-4 space-y-3 relative';
    div.innerHTML = `
        <button type="button" onclick="this.closest('div').remove()" class="absolute top-3 right-3 p-1 rounded-lg hover:bg-danger-50 text-danger-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Booth</label>
                <select name="collections[${idx}][booth_id]" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">${getBoothOptions()}</select>
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Shift <span class="text-danger-500">*</span></label>
                <select name="collections[${idx}][shift]" required class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">
                    <option value="day" ${data.shift === 'night' ? '' : 'selected'}>Day (08:00–20:00)</option>
                    <option value="night" ${data.shift === 'night' ? 'selected' : ''}>Night (20:00–08:00)</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Date In <span class="text-danger-500">*</span></label>
                <input type="date" name="collections[${idx}][date_in]" required value="${data.date_in || '{{ date('Y-m-d') }}'}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Amount Collected (TZS) <span class="text-danger-500">*</span></label>
                <input type="number" name="collections[${idx}][amount_collected]" required min="0" value="${data.amount_collected || ''}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm" placeholder="0">
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Amount Deposited (TZS) <span class="text-danger-500">*</span></label>
                <input type="number" name="collections[${idx}][amount_deposited]" required min="0" value="${data.amount_deposited || ''}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm" placeholder="0">
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Control No. Status</label>
                <select name="collections[${idx}][control_no_status]" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">
                    <option value="pending" ${data.control_no_status === 'provided' ? '' : 'selected'}>Pending</option>
                    <option value="provided" ${data.control_no_status === 'provided' ? 'selected' : ''}>Provided</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Control No.</label>
                <input type="text" name="collections[${idx}][control_no]" value="${data.control_no || ''}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Receipt No.</label>
                <input type="text" name="collections[${idx}][receipt_no]" value="${data.receipt_no || ''}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-gray-600 mb-1">Cashier Name</label>
                <input type="text" name="collections[${idx}][cashier_name]" value="${data.cashier_name || ''}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-maroon-500 focus:ring-2 focus:ring-maroon-200 outline-none text-sm">
            </div>
        </div>
    `;
    document.getElementById('collectionsContainer').appendChild(div);
}

document.getElementById('addCollection').addEventListener('click', () => addCollection());
document.getElementById('stationSelect').addEventListener('change', function() {
    document.querySelectorAll('#collectionsContainer select[name*="[booth_id]"]').forEach(sel => {
        const current = sel.value;
        sel.innerHTML = getBoothOptions();
        sel.value = current;
    });
});

// Add one default entry
addCollection();
</script>
@endsection

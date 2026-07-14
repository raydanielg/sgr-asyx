<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\ParkingLot;
use App\Models\Station;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner,admin_manager']);
    }

    public function index()
    {
        $targets = Target::with('creator')->latest()->paginate(10);
        return view('targets.index', compact('targets'));
    }

    public function create()
    {
        $lots = ParkingLot::where('status', 'active')->get();
        $stations = Station::where('status', 'active')->with('parkingLot')->get();
        return view('targets.create', compact('lots', 'stations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'scope' => 'required|in:company,parking_lot,station',
            'scope_id' => 'nullable|integer',
            'target_type' => 'required|in:contractual,company_set',
            'metric' => 'required|in:revenue_tzs,vehicle_count',
            'period' => 'required|in:daily,weekly,monthly,yearly',
            'target_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($data['scope'] === 'company') {
            $data['scope_id'] = null;
        }

        $data['created_by'] = auth()->id();
        $target = Target::create($data);
        AuditLog::log('create', 'Target', $target->id, "Created target: {$target->target_type} - {$target->metric}");

        return redirect()->route('targets.index')->with('success', 'Target created successfully.');
    }

    public function edit(Target $target)
    {
        $lots = ParkingLot::where('status', 'active')->get();
        $stations = Station::where('status', 'active')->with('parkingLot')->get();
        return view('targets.edit', compact('target', 'lots', 'stations'));
    }

    public function update(Request $request, Target $target)
    {
        $data = $request->validate([
            'scope' => 'required|in:company,parking_lot,station',
            'scope_id' => 'nullable|integer',
            'target_type' => 'required|in:contractual,company_set',
            'metric' => 'required|in:revenue_tzs,vehicle_count',
            'period' => 'required|in:daily,weekly,monthly,yearly',
            'target_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($data['scope'] === 'company') {
            $data['scope_id'] = null;
        }

        $target->update($data);
        AuditLog::log('update', 'Target', $target->id, "Updated target");

        return redirect()->route('targets.index')->with('success', 'Target updated successfully.');
    }

    public function destroy(Target $target)
    {
        $target->delete();
        AuditLog::log('delete', 'Target', $target->id, "Deleted target");

        return redirect()->route('targets.index')->with('success', 'Target deleted.');
    }
}

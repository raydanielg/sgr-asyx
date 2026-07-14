<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\ParkingLot;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner,admin_manager']);
    }

    public function index()
    {
        $stations = Station::with('parkingLot', 'supervisor', 'booths')->latest()->paginate(10);
        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        $lots = ParkingLot::where('status', 'active')->get();
        $supervisors = User::where('role', 'supervisor')->where('status', 'active')->get();
        return view('stations.create', compact('lots', 'supervisors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parking_lot_id' => 'required|exists:parking_lots,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $station = Station::create($data);
        AuditLog::log('create', 'Station', $station->id, "Created station: {$station->name}");

        return redirect()->route('stations.index')->with('success', 'Station created successfully.');
    }

    public function show(Station $station)
    {
        $station->load('parkingLot', 'supervisor', 'booths', 'dailyReports');
        return view('stations.show', compact('station'));
    }

    public function edit(Station $station)
    {
        $lots = ParkingLot::where('status', 'active')->get();
        $supervisors = User::where('role', 'supervisor')->where('status', 'active')->get();
        return view('stations.edit', compact('station', 'lots', 'supervisors'));
    }

    public function update(Request $request, Station $station)
    {
        $data = $request->validate([
            'parking_lot_id' => 'required|exists:parking_lots,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $station->update($data);
        AuditLog::log('update', 'Station', $station->id, "Updated station: {$station->name}");

        return redirect()->route('stations.index')->with('success', 'Station updated successfully.');
    }

    public function destroy(Station $station)
    {
        if ($station->dailyReports()->exists()) {
            return back()->with('error', 'Cannot delete a station with reports. Deactivate instead.');
        }

        $name = $station->name;
        $station->delete();
        AuditLog::log('delete', 'Station', $station->id, "Deleted station: {$name}");

        return redirect()->route('stations.index')->with('success', 'Station deleted.');
    }
}

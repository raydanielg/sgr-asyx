<?php

namespace App\Http\Controllers;

use App\Models\ParkingLot;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ParkingLotController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner,admin_manager']);
    }

    public function index()
    {
        $lots = ParkingLot::withCount('stations')->latest()->paginate(10);
        return view('parking-lots.index', compact('lots'));
    }

    public function create()
    {
        return view('parking-lots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'registered_at' => 'nullable|date',
        ]);

        $lot = ParkingLot::create($data);
        AuditLog::log('create', 'ParkingLot', $lot->id, "Created parking lot: {$lot->name}");

        return redirect()->route('parking-lots.index')->with('success', 'Parking Lot created successfully.');
    }

    public function show(ParkingLot $parkingLot)
    {
        $parkingLot->load('stations.supervisor', 'stations.booths');
        return view('parking-lots.show', compact('parkingLot'));
    }

    public function edit(ParkingLot $parkingLot)
    {
        return view('parking-lots.edit', compact('parkingLot'));
    }

    public function update(Request $request, ParkingLot $parkingLot)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'registered_at' => 'nullable|date',
        ]);

        $parkingLot->update($data);
        AuditLog::log('update', 'ParkingLot', $parkingLot->id, "Updated parking lot: {$parkingLot->name}");

        return redirect()->route('parking-lots.index')->with('success', 'Parking Lot updated successfully.');
    }

    public function destroy(ParkingLot $parkingLot)
    {
        if ($parkingLot->stations()->exists()) {
            return back()->with('error', 'Cannot delete a lot with stations. Deactivate instead.');
        }

        $name = $parkingLot->name;
        $parkingLot->delete();
        AuditLog::log('delete', 'ParkingLot', $parkingLot->id, "Deleted parking lot: {$name}");

        return redirect()->route('parking-lots.index')->with('success', 'Parking Lot deleted.');
    }
}

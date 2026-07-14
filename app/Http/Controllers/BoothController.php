<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Station;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class BoothController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner,admin_manager']);
    }

    public function index()
    {
        $booths = Booth::with('station.parkingLot')->latest()->paginate(10);
        return view('booths.index', compact('booths'));
    }

    public function create()
    {
        $stations = Station::where('status', 'active')->with('parkingLot')->get();
        return view('booths.create', compact('stations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'name' => 'required|string|max:255',
        ]);

        $booth = Booth::create($data);
        AuditLog::log('create', 'Booth', $booth->id, "Created booth: {$booth->name}");

        return redirect()->route('booths.index')->with('success', 'Booth created successfully.');
    }

    public function edit(Booth $booth)
    {
        $stations = Station::where('status', 'active')->with('parkingLot')->get();
        return view('booths.edit', compact('booth', 'stations'));
    }

    public function update(Request $request, Booth $booth)
    {
        $data = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'name' => 'required|string|max:255',
        ]);

        $booth->update($data);
        AuditLog::log('update', 'Booth', $booth->id, "Updated booth: {$booth->name}");

        return redirect()->route('booths.index')->with('success', 'Booth updated successfully.');
    }

    public function destroy(Booth $booth)
    {
        if ($booth->dailyCollections()->exists()) {
            return back()->with('error', 'Cannot delete a booth with collection records.');
        }

        $name = $booth->name;
        $booth->delete();
        AuditLog::log('delete', 'Booth', $booth->id, "Deleted booth: {$name}");

        return redirect()->route('booths.index')->with('success', 'Booth deleted.');
    }
}

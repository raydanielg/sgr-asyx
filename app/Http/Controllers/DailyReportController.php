<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyCollection;
use App\Models\Station;
use App\Models\Booth;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isSupervisor()) {
            $stationIds = $user->stations()->pluck('id');
            $reports = DailyReport::whereIn('station_id', $stationIds)
                ->with('station', 'collections', 'submittedBy')->latest()->paginate(10);
        } else {
            $reports = DailyReport::with('station', 'collections', 'submittedBy', 'reviewedBy')
                ->latest()->paginate(10);
        }

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->isSupervisor()) {
            $stations = $user->stations()->where('status', 'active')->with('booths')->get();
        } else {
            $stations = Station::where('status', 'active')->with('booths')->get();
        }

        return view('reports.create', compact('stations'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'report_date' => 'required|date',
            'comments' => 'nullable|string',
            'collections' => 'required|array|min:1',
            'collections.*.booth_id' => 'nullable|exists:booths,id',
            'collections.*.shift' => 'required|in:day,night',
            'collections.*.date_in' => 'required|date',
            'collections.*.date_out' => 'nullable|date',
            'collections.*.time_in' => 'nullable|string',
            'collections.*.time_out' => 'nullable|string',
            'collections.*.amount_collected' => 'required|numeric|min:0',
            'collections.*.amount_deposited' => 'required|numeric|min:0',
            'collections.*.control_no' => 'nullable|string',
            'collections.*.receipt_no' => 'nullable|string',
            'collections.*.cashier_name' => 'nullable|string',
            'collections.*.control_no_status' => 'required|in:provided,pending',
            'collections.*.comments' => 'nullable|string',
        ]);

        if ($user->isSupervisor()) {
            $stationIds = $user->stations()->pluck('id');
            if (!$stationIds->contains($data['station_id'])) {
                return back()->withInput()->with('error', 'You can only submit reports for your assigned stations.');
            }
        }

        $existing = DailyReport::where('station_id', $data['station_id'])
            ->where('report_date', $data['report_date'])->first();

        if ($existing) {
            return back()->withInput()->with('error', 'A report already exists for this station on this date.');
        }

        $totalVehicles = count($data['collections']);
        $totalExpenses = 0;

        $report = DailyReport::create([
            'station_id' => $data['station_id'],
            'report_date' => $data['report_date'],
            'submitted_by' => $user->id,
            'status' => 'pending',
            'vehicle_count' => $totalVehicles,
            'expenses' => $totalExpenses,
            'net_revenue' => 0,
            'comments' => $data['comments'] ?? null,
            'source' => 'manual',
        ]);

        foreach ($data['collections'] as $col) {
            $difference = $col['amount_collected'] - $col['amount_deposited'];
            $flags = [];
            if ($difference != 0) {
                $flags[] = 'variance';
            }
            if (empty($col['control_no'])) {
                $flags[] = 'missing_control_no';
            }
            if (empty($col['receipt_no'])) {
                $flags[] = 'missing_receipt_no';
            }

            DailyCollection::create([
                'daily_report_id' => $report->id,
                'booth_id' => $col['booth_id'] ?? null,
                'shift' => $col['shift'],
                'date_in' => $col['date_in'],
                'date_out' => $col['date_out'] ?? null,
                'time_in' => $col['time_in'] ?? null,
                'time_out' => $col['time_out'] ?? null,
                'amount_collected' => $col['amount_collected'],
                'amount_deposited' => $col['amount_deposited'],
                'difference' => $difference,
                'control_no' => $col['control_no'] ?? null,
                'receipt_no' => $col['receipt_no'] ?? null,
                'cashier_name' => $col['cashier_name'] ?? null,
                'control_no_status' => $col['control_no_status'],
                'comments' => $col['comments'] ?? null,
                'flags' => $flags,
            ]);
        }

        AuditLog::log('create', 'DailyReport', $report->id, "Submitted daily report for station on {$report->report_date}");

        return redirect()->route('reports.index')->with('success', 'Daily report submitted successfully.');
    }

    public function show(DailyReport $report)
    {
        $user = auth()->user();

        if ($user->isSupervisor()) {
            $stationIds = $user->stations()->pluck('id');
            if (!$stationIds->contains($report->station_id)) {
                abort(403, 'You do not have access to this report.');
            }
        }

        $report->load('station', 'collections.booth', 'submittedBy', 'reviewedBy', 'attachments');
        return view('reports.show', compact('report'));
    }

    public function edit(DailyReport $report)
    {
        $user = auth()->user();

        if ($user->isSupervisor()) {
            $stationIds = $user->stations()->pluck('id');
            if (!$stationIds->contains($report->station_id)) {
                abort(403);
            }
        }

        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be edited.');
        }

        $stations = $user->isSupervisor()
            ? $user->stations()->with('booths')->get()
            : Station::with('booths')->get();

        $report->load('collections.booth');
        return view('reports.edit', compact('report', 'stations'));
    }

    public function update(Request $request, DailyReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be edited.');
        }

        $data = $request->validate([
            'report_date' => 'required|date',
            'comments' => 'nullable|string',
            'collections' => 'required|array|min:1',
            'collections.*.id' => 'nullable|exists:daily_collections,id',
            'collections.*.booth_id' => 'nullable|exists:booths,id',
            'collections.*.shift' => 'required|in:day,night',
            'collections.*.date_in' => 'required|date',
            'collections.*.date_out' => 'nullable|date',
            'collections.*.time_in' => 'nullable|string',
            'collections.*.time_out' => 'nullable|string',
            'collections.*.amount_collected' => 'required|numeric|min:0',
            'collections.*.amount_deposited' => 'required|numeric|min:0',
            'collections.*.control_no' => 'nullable|string',
            'collections.*.receipt_no' => 'nullable|string',
            'collections.*.cashier_name' => 'nullable|string',
            'collections.*.control_no_status' => 'required|in:provided,pending',
            'collections.*.comments' => 'nullable|string',
        ]);

        $report->update([
            'report_date' => $data['report_date'],
            'comments' => $data['comments'] ?? null,
            'vehicle_count' => count($data['collections']),
        ]);

        $existingIds = collect($data['collections'])->pluck('id')->filter()->toArray();
        $report->collections()->whereNotIn('id', $existingIds)->delete();

        foreach ($data['collections'] as $col) {
            $difference = $col['amount_collected'] - $col['amount_deposited'];
            $flags = [];
            if ($difference != 0) $flags[] = 'variance';
            if (empty($col['control_no'])) $flags[] = 'missing_control_no';
            if (empty($col['receipt_no'])) $flags[] = 'missing_receipt_no';

            DailyCollection::updateOrCreate(
                ['id' => $col['id'] ?? null],
                [
                    'daily_report_id' => $report->id,
                    'booth_id' => $col['booth_id'] ?? null,
                    'shift' => $col['shift'],
                    'date_in' => $col['date_in'],
                    'date_out' => $col['date_out'] ?? null,
                    'time_in' => $col['time_in'] ?? null,
                    'time_out' => $col['time_out'] ?? null,
                    'amount_collected' => $col['amount_collected'],
                    'amount_deposited' => $col['amount_deposited'],
                    'difference' => $difference,
                    'control_no' => $col['control_no'] ?? null,
                    'receipt_no' => $col['receipt_no'] ?? null,
                    'cashier_name' => $col['cashier_name'] ?? null,
                    'control_no_status' => $col['control_no_status'],
                    'comments' => $col['comments'] ?? null,
                    'flags' => $flags,
                ]
            );
        }

        AuditLog::log('update', 'DailyReport', $report->id, "Updated daily report");

        return redirect()->route('reports.index')->with('success', 'Daily report updated.');
    }

    public function approve(Request $request, DailyReport $report)
    {
        $report->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->review_notes,
        ]);

        AuditLog::log('approve', 'DailyReport', $report->id, "Approved daily report");

        return back()->with('success', 'Report approved.');
    }

    public function reject(Request $request, DailyReport $report)
    {
        $request->validate(['review_notes' => 'required|string']);

        $report->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->review_notes,
        ]);

        AuditLog::log('reject', 'DailyReport', $report->id, "Rejected daily report: {$request->review_notes}");

        return back()->with('success', 'Report rejected.');
    }

    public function destroy(DailyReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be deleted.');
        }

        $report->collections()->delete();
        $report->delete();
        AuditLog::log('delete', 'DailyReport', $report->id, "Deleted daily report");

        return redirect()->route('reports.index')->with('success', 'Report deleted.');
    }
}

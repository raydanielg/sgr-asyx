<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParkingLot;
use App\Models\Station;
use App\Models\Booth;
use App\Models\Target;
use App\Models\DailyReport;
use App\Models\DailyCollection;
use Illuminate\Database\Seeder;

class SGRSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $owner = User::create([
            'name' => 'Company Owner',
            'email' => 'owner@sgr.co.tz',
            'phone' => '+255700000001',
            'role' => 'owner',
            'status' => 'active',
            'password' => 'password',
        ]);

        $finance = User::create([
            'name' => 'Finance Officer',
            'email' => 'finance@sgr.co.tz',
            'phone' => '+255700000002',
            'role' => 'finance',
            'status' => 'active',
            'password' => 'password',
        ]);

        $admin = User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@sgr.co.tz',
            'phone' => '+255700000003',
            'role' => 'admin_manager',
            'status' => 'active',
            'password' => 'password',
        ]);

        $supervisor = User::create([
            'name' => 'Station Supervisor',
            'email' => 'supervisor@sgr.co.tz',
            'phone' => '+255700000004',
            'role' => 'supervisor',
            'status' => 'active',
            'password' => 'password',
        ]);

        // Parking Lot
        $lot = ParkingLot::create([
            'name' => 'Samia Suluhu SGR Station — Dodoma Lot 2',
            'location' => 'Dodoma',
            'address' => 'SGR Station, Dodoma',
            'capacity' => 500,
            'status' => 'active',
            'registered_at' => '2025-07-01',
        ]);

        // Stations
        $station1 = Station::create([
            'parking_lot_id' => $lot->id,
            'supervisor_id' => $supervisor->id,
            'name' => 'Booth 1 (Main)',
            'status' => 'active',
        ]);

        $station2 = Station::create([
            'parking_lot_id' => $lot->id,
            'supervisor_id' => $supervisor->id,
            'name' => 'Booth 2',
            'status' => 'active',
        ]);

        // Booths
        Booth::create(['station_id' => $station1->id, 'name' => 'Booth 1 Main']);
        Booth::create(['station_id' => $station2->id, 'name' => 'Booth 2']);

        // Targets
        Target::create([
            'scope' => 'station',
            'scope_id' => $station1->id,
            'target_type' => 'contractual',
            'metric' => 'revenue_tzs',
            'period' => 'monthly',
            'target_value' => 10000000,
            'start_date' => '2025-07-01',
            'end_date' => null,
            'created_by' => $admin->id,
        ]);

        Target::create([
            'scope' => 'station',
            'scope_id' => $station1->id,
            'target_type' => 'company_set',
            'metric' => 'revenue_tzs',
            'period' => 'monthly',
            'target_value' => 12000000,
            'start_date' => '2025-07-01',
            'end_date' => null,
            'created_by' => $admin->id,
        ]);

        // Sample daily reports
        $booth1 = Booth::where('station_id', $station1->id)->first();
        $booth2 = Booth::where('station_id', $station2->id)->first();

        for ($i = 5; $i >= 1; $i--) {
            $date = now()->subDays($i);
            $report = DailyReport::create([
                'station_id' => $station1->id,
                'report_date' => $date,
                'submitted_by' => $supervisor->id,
                'status' => $i <= 2 ? 'pending' : 'approved',
                'vehicle_count' => 2,
                'source' => 'manual',
                'reviewed_by' => $i > 2 ? $admin->id : null,
                'reviewed_at' => $i > 2 ? $date->copy()->addDay() : null,
            ]);

            DailyCollection::create([
                'daily_report_id' => $report->id,
                'booth_id' => $booth1->id,
                'shift' => 'day',
                'date_in' => $date,
                'amount_collected' => 350000,
                'amount_deposited' => 350000,
                'difference' => 0,
                'control_no' => 'CN' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'receipt_no' => 'AGNGEPG215055' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'cashier_name' => 'John Doe',
                'control_no_status' => 'provided',
            ]);

            DailyCollection::create([
                'daily_report_id' => $report->id,
                'booth_id' => $booth2->id,
                'shift' => 'night',
                'date_in' => $date,
                'amount_collected' => 280000,
                'amount_deposited' => $i === 3 ? 270000 : 280000,
                'difference' => $i === 3 ? 10000 : 0,
                'control_no' => 'CN' . str_pad($i + 100, 6, '0', STR_PAD_LEFT),
                'receipt_no' => 'AGNGEPG215055' . str_pad($i + 100, 3, '0', STR_PAD_LEFT),
                'cashier_name' => 'Jane Smith',
                'control_no_status' => 'provided',
                'flags' => $i === 3 ? ['variance'] : [],
            ]);
        }
    }
}

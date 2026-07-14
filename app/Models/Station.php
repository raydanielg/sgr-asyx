<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['parking_lot_id', 'supervisor_id', 'name', 'notes', 'status'];

    public function parkingLot()
    {
        return $this->belongsTo(ParkingLot::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function booths()
    {
        return $this->hasMany(Booth::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingLot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'address', 'capacity', 'status', 'registered_at'];

    protected function casts(): array
    {
        return ['registered_at' => 'date'];
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function targets()
    {
        return $this->morphMany(Target::class, 'scopeable', 'scope', 'scope_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booth extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'name'];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function dailyCollections()
    {
        return $this->hasMany(DailyCollection::class);
    }
}

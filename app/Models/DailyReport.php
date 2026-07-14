<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id', 'report_date', 'submitted_by', 'status',
        'vehicle_count', 'expenses', 'net_revenue', 'comments',
        'source', 'file_path', 'reviewed_by', 'reviewed_at', 'review_notes'
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'expenses' => 'decimal:2',
            'net_revenue' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function collections()
    {
        return $this->hasMany(DailyCollection::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function getTotalCollectedAttribute(): float
    {
        return $this->collections->sum('amount_collected');
    }

    public function getTotalDepositedAttribute(): float
    {
        return $this->collections->sum('amount_deposited');
    }

    public function getTotalDifferenceAttribute(): float
    {
        return $this->total_collected - $this->total_deposited;
    }
}

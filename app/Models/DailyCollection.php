<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_id', 'booth_id', 'shift', 'date_in', 'date_out',
        'time_in', 'time_out', 'amount_collected', 'amount_deposited',
        'difference', 'control_no', 'receipt_no', 'cashier_name',
        'control_no_status', 'comments', 'flags'
    ];

    protected function casts(): array
    {
        return [
            'date_in' => 'date',
            'date_out' => 'date',
            'amount_collected' => 'decimal:2',
            'amount_deposited' => 'decimal:2',
            'difference' => 'decimal:2',
            'flags' => 'array',
        ];
    }

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function booth()
    {
        return $this->belongsTo(Booth::class);
    }

    public function hasVariance(): bool
    {
        return $this->difference != 0;
    }
}

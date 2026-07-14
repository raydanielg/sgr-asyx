<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['daily_report_id', 'file_path', 'file_name', 'file_type', 'file_size', 'uploaded_by'];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

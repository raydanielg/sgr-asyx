<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner,admin_manager']);
    }

    public function index()
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);
        return view('audit-logs.index', compact('logs'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('causer')->latest('created_at')->paginate(40);

        return view('admin.activity.index', compact('logs'));
    }
}

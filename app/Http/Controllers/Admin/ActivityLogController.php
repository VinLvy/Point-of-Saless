<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('petugas')->latest();

        // Filter berdasarkan jenis aksi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(10);

        return view('admin.logs.index', compact('logs'));
    }
}

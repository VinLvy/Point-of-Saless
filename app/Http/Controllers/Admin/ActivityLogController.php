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

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(10);
        return view('admin.logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('petugas')->findOrFail($id);

        $badgeClasses = [
            'login' => 'success',
            'logout' => 'danger',
            'tambah' => 'primary',
            'edit' => 'warning',
            'hapus' => 'danger',
            'transaksi' => 'info',
        ];
        $badgeClass = $badgeClasses[$log->action] ?? 'secondary';

        // Ambil hanya data yang berubah
        $changedData = [];
        if ($log->action === 'edit' && is_array($log->old_data) && is_array($log->new_data)) {
            foreach ($log->new_data as $key => $newValue) {
                if (!isset($log->old_data[$key]) || $log->old_data[$key] !== $newValue) {
                    $changedData[$key] = [
                        'old' => $log->old_data[$key] ?? null,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return view('admin.logs.show', compact('log', 'badgeClass', 'changedData'));
    }
}

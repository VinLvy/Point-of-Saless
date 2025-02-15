<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Jika tombol hapus diklik, hapus 10 log terlama
        if ($request->has('delete_oldest')) {
            ActivityLog::oldest()->limit(10)->delete();
            return redirect()->route('admin.logs.index')->with('success', '10 data log terlama telah dihapus.');
        }

        // Filter dan tampilkan data seperti biasa
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

        // Ambil hanya data yang berubah (kecuali timestamps)
        $excludedFields = ['created_at', 'updated_at'];
        $changedData = [];

        if ($log->action === 'edit' && is_array($log->old_data) && is_array($log->new_data)) {
            foreach ($log->new_data as $key => $newValue) {
                if (!in_array($key, $excludedFields) && (!isset($log->old_data[$key]) || $log->old_data[$key] !== $newValue)) {
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

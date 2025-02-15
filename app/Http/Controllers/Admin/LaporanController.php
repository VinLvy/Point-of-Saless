<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $search = $request->input('search');

        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        // Query laporan penjualan
        $query = LaporanPenjualan::with('pelanggan')
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate]);

        // Jika ada pencarian kode transaksi
        if (!empty($search)) {
            $query->where('kode_transaksi', 'like', "%$search%");
        }

        $laporan = $query->orderBy('tanggal_transaksi', 'desc')->get();

        return view('admin.laporan.index', compact('laporan', 'startDate', 'endDate', 'search'));
    }


    public function show($id)
    {
        // Ambil detail laporan penjualan berdasarkan ID dengan relasi pelanggan dan detail penjualan
        $laporan = LaporanPenjualan::with(['pelanggan', 'detail.itemBarang', 'Petugas'])
            ->findOrFail($id);

        return view('admin.laporan.detail', compact('laporan'));
    }
}

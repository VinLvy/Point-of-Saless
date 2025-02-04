<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal awal dan akhir dari request
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Format tanggal untuk query
        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        // Ambil data laporan penjualan dengan relasi pelanggan dan detail penjualan
        $laporan = LaporanPenjualan::with(['pelanggan', 'detail.produk'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->get();

        return view('admin.laporan.index', compact('laporan', 'startDate', 'endDate'));
    }

    public function show($id)
    {
        // Ambil detail laporan penjualan berdasarkan ID dengan relasi pelanggan dan detail penjualan
        $laporan = LaporanPenjualan::with(['pelanggan', 'detail.produk'])
            ->findOrFail($id);

        return view('admin.laporan.detail', compact('laporan'));
    }
}
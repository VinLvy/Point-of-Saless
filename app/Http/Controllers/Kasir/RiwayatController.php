<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\DetailLaporanPenjualan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        $riwayat = LaporanPenjualan::with(['pelanggan', 'petugas'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kasir.riwayat.index', compact('riwayat', 'startDate', 'endDate'));
    }

    public function nota($kode_transaksi)
    {
        $laporan = LaporanPenjualan::where('kode_transaksi', $kode_transaksi)
            ->with(['pelanggan'])
            ->firstOrFail();

        $detailTransaksi = DetailLaporanPenjualan::where('laporan_penjualan_id', $laporan->id)->get();

        return view('kasir.riwayat.nota', compact('laporan', 'detailTransaksi'));
    }

    public function show($id)
    {
        // Ambil detail laporan penjualan berdasarkan ID dengan relasi pelanggan dan detail penjualan
        $laporan = LaporanPenjualan::with(['pelanggan', 'detail.itemBarang', 'Petugas'])
            ->findOrFail($id);

        return view('kasir.riwayat.detail', compact('laporan'));
    }
}

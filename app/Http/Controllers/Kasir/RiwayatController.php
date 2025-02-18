<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\DetailLaporanPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        $petugasId = Auth::id(); // ID petugas yang sedang login

        $riwayat = LaporanPenjualan::with([
            'pelanggan' => function ($query) {
                $query->withTrashed(); // Mengambil pelanggan yang sudah soft delete
            },
            'petugas'
        ])
            ->where('petugas_id', $petugasId) // Filter berdasarkan petugas yang login
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kasir.riwayat.index', compact('riwayat', 'startDate', 'endDate'));
    }

    public function nota($kode_transaksi)
    {
        $laporan = LaporanPenjualan::where('kode_transaksi', $kode_transaksi)
            ->with([
                'pelanggan' => function ($query) {
                    $query->withTrashed(); // Mengambil pelanggan yang sudah soft delete
                }
            ])
            ->firstOrFail();

        $detailTransaksi = DetailLaporanPenjualan::where('laporan_penjualan_id', $laporan->id)
            ->with([
                'itemBarang' => function ($query) {
                    $query->withTrashed(); // Mengambil barang yang sudah soft delete
                }
            ])
            ->get();

        return view('kasir.riwayat.nota', compact('laporan', 'detailTransaksi'));
    }

    public function show($id)
    {
        // Ambil detail laporan penjualan berdasarkan ID dengan relasi pelanggan, detail penjualan, dan petugas
        $laporan = LaporanPenjualan::with([
            'pelanggan' => function ($query) {
                $query->withTrashed(); // Mengambil pelanggan yang sudah soft delete
            },
            'detail.itemBarang' => function ($query) {
                $query->withTrashed(); // Mengambil barang yang sudah soft delete
            },
            'petugas'
        ])->findOrFail($id);

        return view('kasir.riwayat.detail', compact('laporan'));
    }
}

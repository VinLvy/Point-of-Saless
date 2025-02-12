<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailLaporanPenjualan;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;

class LaporanBarangTerjualController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanPenjualan::with('detail.itemBarang');

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan pencarian kode transaksi atau nama barang
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%$search%")
                    ->orWhereHas('detail.itemBarang', function ($q) use ($search) {
                        $q->where('nama_barang', 'like', "%$search%");
                    });
            });
        }

        $laporan = $query->get();

        return view('admin.terjual.index', compact('laporan'));
    }
}

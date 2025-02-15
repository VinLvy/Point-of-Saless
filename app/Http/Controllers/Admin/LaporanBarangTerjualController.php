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

        // Filter berdasarkan tanggal transaksi
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '=', $request->start_date);
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
        $query->orderBy('created_at', 'desc');

        $laporan = $query->paginate(10);

        return view('admin.terjual.index', compact('laporan'));
    }
}

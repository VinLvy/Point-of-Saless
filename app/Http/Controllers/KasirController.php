<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemBarang;
use App\Models\Stok;
use App\Models\Petugas;
use App\Models\Pelanggan;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function dashboard()
    {
        $jumlah_barang = ItemBarang::count();
        $total_petugas = Petugas::where('role', '!=', 'administrator')->count();
        $total_pelanggan = Pelanggan::count();

        // Total income hari ini
        $total_income_hari_ini = LaporanPenjualan::whereDate('created_at', Carbon::today())->sum('total_akhir');

        // Barang yang hampir habis stoknya
        $barang_kurang_stok = ItemBarang::with('stok')->get()->filter(function ($item) {
            $total_stok = $item->stok->sum('jumlah_stok'); // Hitung stok di sini
            return $total_stok <= $item->minimal_stok; // Pastikan kondisi ≤ bukan hanya <
        });
        

        // Data pendapatan dalam 1 minggu terakhir untuk chart
        $labels_pendapatan = [];
        $data_pendapatan = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i)->format('Y-m-d');
            $labels_pendapatan[] = Carbon::parse($tanggal)->format('d M');
            $data_pendapatan[] = LaporanPenjualan::whereDate('created_at', $tanggal)->sum('total_akhir');
        }

        $petugas = Auth::user()->nama_petugas ?? 'Tidak Diketahui';

        return view('kasir.dashboard', compact(
            'jumlah_barang', 
            'total_petugas', 
            'total_pelanggan', 
            'total_income_hari_ini', 
            'barang_kurang_stok', 
            'petugas',
            'labels_pendapatan',
            'data_pendapatan'
        ));
    }
}

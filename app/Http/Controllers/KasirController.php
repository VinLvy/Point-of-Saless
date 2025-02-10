<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemBarang;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function dashboard()
    {
        $jumlah_barang = ItemBarang::count();
        $barang_kurang_stok = ItemBarang::whereColumn('stok', '<', 'minimal_stok')->get();
        $petugas = Auth::user()->nama_petugas ?? 'Tidak Diketahui';

        return view('kasir.dashboard', compact('jumlah_barang', 'barang_kurang_stok', 'petugas'));
    }
}
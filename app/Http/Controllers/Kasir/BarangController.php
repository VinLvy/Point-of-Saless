<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\ItemBarang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemBarang::with('kategori');

        // Filter berdasarkan pencarian nama barang atau kode barang
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Ambil hasil dengan urutan kode barang
        $barang = $query->orderBy('kode_barang', 'asc')->get();
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();

        return view('kasir.barang.index', compact('barang', 'kategori'));
    }
}

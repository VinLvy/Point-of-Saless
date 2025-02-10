<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\ItemBarang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $barang = ItemBarang::with('kategori')->orderBy('kode_barang', 'asc')->get();
        return view('kasir.barang.index', compact('barang'));
    }
}
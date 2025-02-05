<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemBarang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class ItemBarangController extends Controller
{
    public function index()
    {
        $barang = ItemBarang::with('kategori')->orderBy('kode_barang', 'asc')->get();
        return view('admin.barang.index', compact('barang'));
    }
}

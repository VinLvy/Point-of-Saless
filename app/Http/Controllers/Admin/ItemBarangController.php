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

    public function create()
    {
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        return view('admin.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_kedaluarsa' => 'required|date',
            'tanggal_pembelian' => 'required|date',
            'harga_beli' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori_barang,id',
        ]);

        $harga_beli = $request->harga_beli;

        // Perhitungan harga jual
        $harga_jual_1 = round($harga_beli * 1.1); // HPP + 10%
        $harga_jual_2 = round($harga_beli * 1.2); // HPP + 20%
        $harga_jual_3 = round($harga_beli * 1.3); // HPP + 30%

        // Simpan ke database
        ItemBarang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'tanggal_kedaluarsa' => $request->tanggal_kedaluarsa,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'harga_beli' => $harga_beli,
            'harga_jual_1' => $harga_jual_1,
            'harga_jual_2' => $harga_jual_2,
            'harga_jual_3' => $harga_jual_3,
            'stok' => $request->stok,
            'minimal_stok' => $request->minimal_stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show($id)
    {
        $barang = ItemBarang::with('kategori')->findOrFail($id);
        return view('admin.barang.show', compact('barang'));
    }
}

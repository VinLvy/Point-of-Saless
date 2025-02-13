<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemBarang;
use App\Models\Stok;

class StokController extends Controller
{
    // Menampilkan daftar stok barang
    public function index()
    {
        $stok = Stok::with('itemBarang')->get();
        return view('admin.stok.index', compact('stok'));
    }

    // Menampilkan form tambah stok
    public function create()
    {
        $items = ItemBarang::all();
        return view('admin.stok.create', compact('items'));
    }

    // Menyimpan stok baru
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:item_barang,id',
            'jumlah_stok' => 'required|integer|min:1',
            'expired_date' => 'required|date',
            'buy_date' => 'required|date',
        ]);

        Stok::create([
            'item_id' => $request->item_id,
            'jumlah_stok' => $request->jumlah_stok,
            'expired_date' => $request->expired_date,
            'buy_date' => $request->buy_date,
        ]);

        return redirect()->route('admin.stok.create')->with('success', 'Stok berhasil ditambahkan.');
    }
}

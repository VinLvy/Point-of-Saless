<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemBarang;
use App\Models\Stok;

class StokController extends Controller
{
    public function index()
    {
        $stok = Stok::with('itemBarang')->get();
        return view('admin.stok.index', compact('stok'));
    }

    public function create()
    {
        $items = ItemBarang::all();
        return view('admin.stok.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:item_barang,id',
            'jumlah_stok' => 'required|integer|min:1',
            'expired_date' => 'required|date',
            'buy_date' => 'required|date',
        ]);

        Stok::create($request->only(['item_id', 'jumlah_stok', 'expired_date', 'buy_date']));

        return redirect()->route('admin.stok.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $stok = Stok::findOrFail($id);
        $items = ItemBarang::all();
        return view('admin.stok.edit', compact('stok', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:item_barang,id',
            'jumlah_stok' => 'required|integer|min:1',
            'expired_date' => 'required|date',
            'buy_date' => 'required|date',
        ]);

        $stok = Stok::findOrFail($id);
        $stok->update($request->only(['item_id', 'jumlah_stok', 'expired_date', 'buy_date']));

        return redirect()->route('admin.stok.index')->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = Stok::findOrFail($id);
        $stok->delete();

        return redirect()->route('admin.stok.index')->with('success', 'Stok berhasil dihapus.');
    }
}

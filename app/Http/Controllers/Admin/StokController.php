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
            'expired_date' => 'required|date|after:buy_date',
            'buy_date' => 'required|date',
        ], [
            'expired_date.after' => 'Tanggal kedaluwarsa harus setelah tanggal pembelian.',
        ]);

        // Pastikan expired_date minimal seminggu setelah buy_date
        $buyDate = strtotime($request->buy_date);
        $minExpiredDate = strtotime('+7 days', $buyDate);

        if (strtotime($request->expired_date) < $minExpiredDate) {
            return redirect()->back()->withErrors(['expired_date' => 'Tanggal kedaluwarsa minimal harus seminggu setelah tanggal pembelian.'])->withInput();
        }

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
            'expired_date' => 'required|date|after:buy_date',
            'buy_date' => 'required|date',
        ], [
            'expired_date.after' => 'Tanggal kedaluwarsa sudah terlewat sebelum tanggal pembelian.',
        ]);

        // Pastikan expired_date minimal seminggu setelah buy_date
        $buyDate = strtotime($request->buy_date);
        $minExpiredDate = strtotime('+7 days', $buyDate);

        if (strtotime($request->expired_date) < $minExpiredDate) {
            return redirect()->back()->withErrors(['expired_date' => 'Tanggal kedaluwarsa minimal harus seminggu setelah tanggal pembelian.'])->withInput();
        }

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

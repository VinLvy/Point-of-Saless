<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemBarang;
use App\Models\KategoriBarang;
use App\Models\Stok;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ItemBarangController extends Controller
{
    public function index()
    {
        $barang = ItemBarang::with('kategori', 'stok')->orderBy('kode_barang', 'asc')->get();
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
            'nama_barang' => 'required|string|max:255|unique:item_barang,nama_barang,NULL,id,deleted_at,NULL',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori_barang,id',
        ], [
            'nama_barang.unique' => 'Nama barang sudah digunakan. Silakan gunakan nama lain.'
        ]);

        $harga_beli = $request->harga_beli;
        $harga_jual_1 = round($harga_beli * 1.1);
        $harga_jual_2 = round($harga_beli * 1.2);
        $harga_jual_3 = round($harga_beli * 1.3);

        $lastBarang = ItemBarang::withTrashed()->orderBy('kode_barang', 'desc')->first();
        $nextKode = $lastBarang ? 'BRG' . str_pad((int) substr($lastBarang->kode_barang, 3) + 1, 3, '0', STR_PAD_LEFT) : 'BRG001';

        $barang = ItemBarang::create([
            'kode_barang' => $nextKode,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'harga_beli' => $harga_beli,
            'harga_jual_1' => $harga_jual_1,
            'harga_jual_2' => $harga_jual_2,
            'harga_jual_3' => $harga_jual_3,
            'minimal_stok' => $request->minimal_stok,
            'kategori_id' => $request->kategori_id,
        ]);

        $this->logActivity('tambah', 'item_barang', $barang->id, null, $barang->toArray());

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = ItemBarang::with('stok')->findOrFail($id);
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255|unique:item_barang,nama_barang,' . $id,
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori_barang,id',
        ], [
            'nama_barang.unique' => 'Nama barang sudah digunakan. Silakan gunakan nama lain.'
        ]);

        $barang = ItemBarang::findOrFail($id);
        $oldData = $barang->toArray();

        $harga_beli = $request->harga_beli;
        $harga_jual_1 = round($harga_beli * 1.1);
        $harga_jual_2 = round($harga_beli * 1.2);
        $harga_jual_3 = round($harga_beli * 1.3);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'harga_beli' => $harga_beli,
            'harga_jual_1' => $harga_jual_1,
            'harga_jual_2' => $harga_jual_2,
            'harga_jual_3' => $harga_jual_3,
            'minimal_stok' => $request->minimal_stok,
            'kategori_id' => $request->kategori_id,
        ]);

        $this->logActivity('edit', 'item_barang', $id, $oldData, $barang->toArray());

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = ItemBarang::findOrFail($id);
        $oldData = $barang->toArray();
        $namaBarang = $barang->nama_barang;

        $barang->stok()->delete();
        $barang->delete();

        $this->logActivity('hapus', 'item_barang', $id, ['nama_barang' => $namaBarang], null);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }

    private function logActivity($action, $model, $model_id, $oldData = null, $newData = null)
    {
        ActivityLog::create([
            'petugas_id' => Auth::id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $model_id,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}

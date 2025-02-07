<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemBarang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

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
        $barang = ItemBarang::create([
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

        // Simpan log aktivitas
        $this->logActivity('tambah', 'item_barang', $barang->id, null, $barang->toArray());

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = ItemBarang::findOrFail($id);
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
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

        $barang = ItemBarang::findOrFail($id);
        $oldData = $barang->toArray(); // Simpan data sebelum diupdate

        $harga_beli = $request->harga_beli;

        // Perhitungan harga jual
        $harga_jual_1 = round($harga_beli * 1.1); // HPP + 10%
        $harga_jual_2 = round($harga_beli * 1.2); // HPP + 20%
        $harga_jual_3 = round($harga_beli * 1.3); // HPP + 30%

        $barang->update([
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

        // Simpan log aktivitas
        $this->logActivity('edit', 'item_barang', $id, $oldData, $barang->toArray());

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function show($id)
    {
        $barang = ItemBarang::with('kategori')->findOrFail($id);
        return view('admin.barang.show', compact('barang'));
    }

    public function destroy($id)
    {
        $barang = ItemBarang::findOrFail($id);
        $oldData = $barang->toArray(); // Simpan data sebelum dihapus
        $namaBarang = $barang->nama_barang; // Simpan nama barang sebelum dihapus

        $barang->delete();

        // Simpan log aktivitas
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::orderByRaw("CAST(SUBSTRING(kode_kategori, 4) AS UNSIGNED) ASC")->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori_barang,nama_kategori|max:255',
        ]);

        $kategori = KategoriBarang::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        $this->logActivity('tambah', 'kategori_barang', $kategori->id, null, $kategori->toArray());

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori_barang,nama_kategori,' . $id . '|max:255',
        ]);

        $kategori = KategoriBarang::findOrFail($id);
        $oldData = $kategori->toArray(); // Simpan data sebelum update

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        $this->logActivity('edit', 'kategori_barang', $kategori->id, $oldData, $kategori->toArray());

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $oldData = $kategori->toArray(); // Simpan data sebelum dihapus
        $namaKategori = $kategori->nama_kategori; // Simpan nama kategori sebelum dihapus
    
        $kategori->delete();
    
        // Kirimkan $namaKategori sebagai bagian dari old_data agar tetap bisa diakses
        $this->logActivity('hapus', 'kategori_barang', $id, ['nama_kategori' => $namaKategori], null);
    
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
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

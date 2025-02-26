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
            'nama_kategori' => [
                'required',
                'max:255',
                'regex:/^[A-Z][a-z\s]*$/'
            ],
        ], [
            'nama_kategori.regex' => 'Nama kategori harus diawali huruf kapital dan tidak boleh mengandung huruf kapital di tengah.',
        ]);

        // Periksa apakah kategori sudah ada termasuk yang dihapus secara soft delete
        $kategori = KategoriBarang::withTrashed()
            ->where('nama_kategori', $request->nama_kategori)
            ->first();

        if ($kategori) {
            if ($kategori->trashed()) {
                // Jika kategori ada tapi dihapus, lakukan restore
                $kategori->restore();
                $this->logActivity('restore', 'kategori_barang', $kategori->id, null, $kategori->toArray());
                return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dikembalikan!');
            } else {
                // Jika kategori ada dan tidak terhapus, tampilkan error
                return redirect()->route('admin.kategori.index')->with('error', 'Nama Kategori sudah ada. Silakan gunakan nama lain.');
            }
        }

        // Jika tidak ada kategori dengan nama yang sama, buat baru
        $kategori = KategoriBarang::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        $this->logActivity('tambah', 'kategori_barang', $kategori->id, null, $kategori->toArray());

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // public function edit($id)
    // {
    //     $kategori = KategoriBarang::findOrFail($id);
    //     return view('admin.kategori.edit', compact('kategori'));
    // }

    public function updateAjax(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kategori_barang,id',
            'nama_kategori' => 'required|unique:kategori_barang,nama_kategori,' . $request->id . '|max:255',
        ], [
            'nama_kategori.unique' => 'Nama Kategori sudah ada. Silakan gunakan nama lain.'
        ]);

        $kategori = KategoriBarang::findOrFail($request->id);
        $oldData = $kategori->toArray();

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        $this->logActivity('edit', 'kategori_barang', $kategori->id, $oldData, $kategori->toArray());

        return response()->json(['success' => 'Kategori berhasil diperbarui!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori_barang,nama_kategori,' . $id . '|max:255',
        ], [
            'nama_kategori.unique' => 'Nama Kategori sudah ada. Silakan gunakan nama lain.'
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

        // Periksa apakah kategori digunakan oleh barang
        if ($kategori->itemBarang()->exists()) {
            return redirect()->route('admin.kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh barang.');
        }

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

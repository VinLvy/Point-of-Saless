<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::where('nama_pelanggan', '!=', 'Non Member')->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggan,email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'poin_membership' => 'integer|min:0',
            'tipe_pelanggan' => 'required|in:tipe 1,tipe 2,tipe 3'
        ]);        

        $pelanggan = Pelanggan::create($request->only([
            'nama_pelanggan', 'email', 'no_hp', 'alamat', 'poin_membership', 'tipe_pelanggan'
        ]));

        // Simpan log aktivitas
        $this->logActivity('tambah', $pelanggan, null, $pelanggan->toArray());

        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggan,email,' . $pelanggan->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'poin_membership' => 'integer|min:0',
            'tipe_pelanggan' => 'required|in:tipe 1,tipe 2,tipe 3'
        ]);

        $oldData = $pelanggan->toArray();
        $pelanggan->update($request->only([
            'nama_pelanggan', 'email', 'no_hp', 'alamat', 'poin_membership', 'tipe_pelanggan'
        ]));

        // Simpan log aktivitas
        $this->logActivity('edit', $pelanggan, $oldData, $pelanggan->toArray());

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $oldData = $pelanggan->toArray();
        $pelanggan->delete();

        // Simpan log aktivitas
        $this->logActivity('hapus', $pelanggan, $oldData, null);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    // Fungsi untuk mencatat aktivitas
    private function logActivity($action, $model, $oldData, $newData)
    {
        ActivityLog::create([
            'petugas_id' => Auth::id(),
            'action' => $action,
            'model' => class_basename($model),
            'model_id' => $model->id,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}

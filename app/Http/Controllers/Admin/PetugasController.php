<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::where('role', '!=', 'administrator')->get();

        $deletedPetugas = Petugas::onlyTrashed()->get();

        return view('admin.petugas.index', compact('petugas', 'deletedPetugas'));
    }

    public function create()
    {
        return view('admin.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255|unique:petugas,nama_petugas',
            'email' => 'required|email|unique:petugas,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => 'required|in:kasir',
        ], [
            'nama_petugas.unique' => 'Nama petugas sudah digunakan. Silakan gunakan nama lain.',
            'email.unique' => 'Email sudah digunakan. Silakan gunakan email lain.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, serta angka.'
        ]);


        $petugas = Petugas::create([
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Simpan log aktivitas
        $this->logActivity('tambah', $petugas, null, $petugas->toArray());

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('admin.petugas.edit', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'nama_petugas' => 'required|string|max:255|unique:petugas,nama_petugas,' . $petugas->id,
            'email' => 'required|email|unique:petugas,email,' . $petugas->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => 'required|in:kasir',
        ], [
            'nama_petugas.unique' => 'Nama petugas sudah digunakan. Silakan gunakan nama lain.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, serta angka.'
        ]);

        $oldData = $petugas->toArray();
        $petugas->update([
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $petugas->password,
        ]);

        // Simpan log aktivitas
        $this->logActivity('edit', $petugas, $oldData, $petugas->toArray());

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $oldData = $petugas->toArray();
        $petugas->delete();

        // Simpan log aktivitas
        $this->logActivity('hapus', $petugas, $oldData, null);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }

    public function restore($id)
    {
        $petugas = Petugas::onlyTrashed()->findOrFail($id);
        $petugas->restore();

        // Simpan log aktivitas
        $this->logActivity('restore', $petugas, null, $petugas->toArray());

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil direstore.');
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

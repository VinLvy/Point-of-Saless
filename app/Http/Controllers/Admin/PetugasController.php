<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::where('role', '!=', 'administrator')->get();
        return view('admin.petugas.index', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas,email',
            'password' => 'required|min:6',
            'role' => 'required|in:petugas,kasir',
        ]);

        Petugas::create([
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id); // Cari petugas berdasarkan ID

        return view('admin.petugas.edit', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id); // Cari petugas berdasarkan ID

        // Validasi input
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas,email,' . $petugas->id, // Abaikan email petugas saat ini
            'password' => 'nullable|min:6', // Password opsional
            'role' => 'required|in:petugas,kasir',
        ]);

        // Update data petugas
        $petugas->update([
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $petugas->password, // Update password jika ada
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}

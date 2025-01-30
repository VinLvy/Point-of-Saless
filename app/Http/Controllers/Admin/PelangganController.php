<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'email' => 'nullable|email|unique:pelanggan,email',
            'no_hp' => 'required',
            'alamat' => 'nullable',
            'poin' => 'integer|min:0'
        ]);

        Pelanggan::create($request->all());
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'email' => 'nullable|email|unique:pelanggan,email,' . $pelanggan->id,
            'no_hp' => 'required',
            'alamat' => 'nullable',
            'poin_membership' => 'integer|min:0'
        ]);

        $pelanggan->update($request->all());
        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}

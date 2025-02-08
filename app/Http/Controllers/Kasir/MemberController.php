<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index()
    {
        $member = Pelanggan::all();
        return view('kasir.member.index', compact('member'));
    }

    public function create()
    {
        return view('kasir.member.create');
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

        $member = Pelanggan::create($request->only([
            'nama_pelanggan', 'email', 'no_hp', 'alamat', 'poin_membership', 'tipe_pelanggan'
        ]));

        // Simpan log aktivitas
        $this->logActivity('tambah', $member, null, $member->toArray());

        return redirect()->route('kasir.member.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $member)
    {
        return view('kasir.member.edit', compact('member'));
    }

    public function update(Request $request, Pelanggan $member)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggan,email,' . $member->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'poin_membership' => 'integer|min:0',
            'tipe_pelanggan' => 'required|in:tipe 1,tipe 2,tipe 3'
        ]);

        $oldData = $member->toArray();
        $member->update($request->only([
            'nama_pelanggan', 'email', 'no_hp', 'alamat', 'poin_membership', 'tipe_pelanggan'
        ]));

        // Simpan log aktivitas
        $this->logActivity('edit', $member, $oldData, $member->toArray());

        return redirect()->route('kasir.member.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $member)
    {
        $oldData = $member->toArray();
        $member->delete();

        // Simpan log aktivitas
        $this->logActivity('hapus', $member, $oldData, null);

        return redirect()->route('kasir.member.index')->with('success', 'Pelanggan berhasil dihapus.');
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

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
        $member = Pelanggan::where('nama_pelanggan', '!=', 'Non Member')->get();
        return view('kasir.member.index', compact('member'));
    }

    public function create()
    {
        return view('kasir.member.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?:[A-Z][a-z]*)(?: [A-Z][a-z]*)*$/',
            ],
            'email' => 'nullable|email|unique:pelanggan,email',
            'no_hp' => 'required|numeric|digits_between:8,20|unique:pelanggan,no_hp',
            'alamat' => 'nullable|string',
            'poin_membership' => 'integer|min:0',
            'tipe_pelanggan' => 'required|in:tipe 1,tipe 2,tipe 3'
        ], [
            'nama_pelanggan.regex' => 'Nama harus diawali huruf kapital.',
            'email.unique' => 'Email ini sudah digunakan, silakan gunakan email lain.',
            'no_hp.numeric' => 'Nomor HP hanya boleh mengandung angka.',
            'no_hp.digits_between' => 'Nomor HP harus memiliki panjang antara 8 hingga 20 digit.',
            'no_hp.unique' => 'Nomor HP ini sudah digunakan, silakan gunakan nomor lain.'
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
            'nama_pelanggan' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?:[A-Z][a-z]*)(?: [A-Z][a-z]*)*$/',
            ],
            'email' => 'nullable|email|unique:pelanggan,email,' . $member->id,
            'no_hp' => 'required|numeric|digits_between:8,20|unique:pelanggan,no_hp,' . $member->id,
            'alamat' => 'nullable|string',
            'poin_membership' => 'integer|min:0',
            'tipe_pelanggan' => 'required|in:tipe 1,tipe 2,tipe 3'
        ], [
            'nama_pelanggan.regex' => 'Nama harus diawali huruf kapital.',
            'email.unique' => 'Email ini sudah digunakan, silakan gunakan email lain.',
            'no_hp.numeric' => 'Nomor HP hanya boleh mengandung angka.',
            'no_hp.digits_between' => 'Nomor HP harus memiliki panjang antara 8 hingga 20 digit.',
            'no_hp.unique' => 'Nomor HP ini sudah digunakan, silakan gunakan nomor lain.'
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

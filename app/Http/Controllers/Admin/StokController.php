<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemBarang;
use App\Models\Stok;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function index()
    {
        $this->hapusStokKadaluarsa();
        $this->hapusStokHabis();
        $stok = Stok::with('itemBarang')->get();
        return view('admin.stok.index', compact('stok'));
    }

    public function create()
    {
        $items = ItemBarang::all();
        return view('admin.stok.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:item_barang,id',
            'jumlah_stok' => 'required|integer|min:1',
            'expired_date' => 'required|date|after:buy_date',
            'buy_date' => 'required|date',
        ], [
            'expired_date.after' => 'Tanggal kedaluwarsa harus setelah tanggal pembelian.',
        ]);

        $buyDate = strtotime($request->buy_date);
        $minExpiredDate = strtotime('+7 days', $buyDate);

        if (strtotime($request->expired_date) < $minExpiredDate) {
            return redirect()->back()->withErrors(['expired_date' => 'Tanggal kedaluwarsa minimal harus seminggu setelah tanggal pembelian.'])->withInput();
        }

        $stok = Stok::create($request->only(['item_id', 'jumlah_stok', 'expired_date', 'buy_date']));
        $this->logActivity('tambah', 'stok', $stok->id, null, $stok->toArray());

        return redirect()->route('admin.stok.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $stok = Stok::findOrFail($id);
        $items = ItemBarang::all();
        return view('admin.stok.edit', compact('stok', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:item_barang,id',
            'jumlah_stok' => 'required|integer|min:1',
            'expired_date' => 'required|date|after:buy_date',
            'buy_date' => 'required|date',
        ], [
            'expired_date.after' => 'Tanggal kedaluwarsa harus setelah tanggal pembelian.',
        ]);

        $buyDate = strtotime($request->buy_date);
        $minExpiredDate = strtotime('+7 days', $buyDate);

        if (strtotime($request->expired_date) < $minExpiredDate) {
            return redirect()->back()->withErrors(['expired_date' => 'Tanggal kedaluwarsa minimal harus seminggu setelah tanggal pembelian.'])->withInput();
        }

        $stok = Stok::findOrFail($id);
        $oldData = $stok->toArray();

        $stok->update($request->only(['item_id', 'jumlah_stok', 'expired_date', 'buy_date']));
        $this->logActivity('edit', 'stok', $stok->id, $oldData, $stok->toArray());

        return redirect()->route('admin.stok.index')->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = Stok::findOrFail($id);
        $oldData = $stok->toArray();
        $stok->delete();

        $this->logActivity('hapus', 'stok', $id, $oldData, null);

        return redirect()->route('admin.stok.index')->with('success', 'Stok berhasil dihapus.');
    }

    private function hapusStokKadaluarsa()
    {
        $today = now()->toDateString();
        $expiredStok = Stok::where('expired_date', '<', $today)->get();

        foreach ($expiredStok as $stok) {
            $oldData = $stok->toArray();
            $stok->delete();

            $this->logActivity('hapus', 'stok', $stok->id, $oldData, null);
        }
    }

    private function hapusStokHabis()
    {
        $stokHabis = Stok::where('jumlah_stok', '<=', 0)->get();

        foreach ($stokHabis as $stok) {
            $oldData = $stok->toArray();
            $stok->delete();

            $this->logActivity('hapus', 'stok', $stok->id, $oldData, null);
        }
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

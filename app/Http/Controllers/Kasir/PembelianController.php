<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\ItemBarang;
use App\Models\LaporanPenjualan;
use App\Models\DetailLaporanPenjualan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $produk = ItemBarang::all();
        return view('kasir.pembelian.index', compact('pelanggan', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required',
            'produk_id' => 'required|array',
            'jumlah' => 'required|array',
            'total_bayar' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'uang_dibayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);
            $totalBelanja = 0;
            $items = [];

            foreach ($request->produk_id as $index => $produkId) {
                $produk = ItemBarang::findOrFail($produkId);
                $jumlah = $request->jumlah[$index];

                if ($produk->stok < $jumlah) {
                    return redirect()->route('kasir.pembelian.index')->with('error', "Stok barang '{$produk->nama_barang}' tidak mencukupi!");
                }

                $hargaJual = match ($pelanggan->tipe_pelanggan) {
                    'tipe 1' => $produk->harga_jual_1,
                    'tipe 2' => $produk->harga_jual_2,
                    'tipe 3' => $produk->harga_jual_3,
                };

                $totalHarga = $hargaJual * $jumlah;
                $totalBelanja += $totalHarga;

                $items[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga' => $hargaJual,
                    'total_harga' => $totalHarga,
                ];
            }

            // Menghitung diskon dan total akhir
            $diskonPersen = $request->diskon ?? 0;
            $diskonNominal = ($diskonPersen / 100) * $totalBelanja;
            $totalAkhir = ($totalBelanja - $diskonNominal) * 1.12;

            if ($request->uang_dibayar < $totalAkhir) {
                return redirect()->back()
                    ->withInput($request->all())
                    ->with('warning', 'Uang tidak cukup!');
            }

            $kembalian = $request->uang_dibayar - $totalAkhir;
            $poinDidapat = in_array($pelanggan->tipe_pelanggan, ['tipe 1', 'tipe 2'])
                ? floor($totalBelanja * 0.02)
                : 0;

            // Simpan transaksi
            $laporan = new LaporanPenjualan([
                'pelanggan_id' => $pelanggan->id,
                'petugas_id' => Auth::id(),
                'tipe_pelanggan' => $pelanggan->tipe_pelanggan,
                'total_belanja' => $totalBelanja,
                'diskon' => $diskonPersen,
                'poin_digunakan' => 0,
                'poin_didapat' => $poinDidapat,
                'total_akhir' => $totalAkhir,
                'uang_dibayar' => $request->uang_dibayar,
                'kembalian' => $kembalian,
                'tanggal_transaksi' => now(),
            ]);
            $laporan->save();
            $laporan->detail()->createMany($items);

            // Update stok produk
            foreach ($request->produk_id as $index => $produkId) {
                $produk = ItemBarang::findOrFail($produkId);
                $produk->stok -= $request->jumlah[$index];
                $produk->save();
            }

            // Tambahkan poin ke akun pelanggan
            if ($poinDidapat > 0) {
                $pelanggan->increment('poin_membership', $poinDidapat);
            }

            // Simpan log aktivitas
            $this->logActivity('transaksi', $laporan, null, $laporan->toArray());

            DB::commit();
            return redirect()->route('kasir.pembelian.index')->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kasir.pembelian.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

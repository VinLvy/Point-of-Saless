<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\ItemBarang;
use App\Models\Stok;
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
        $produk = ItemBarang::with('stok')->get();
        $kode_transaksi = session('kode_transaksi');
        session()->forget('kode_transaksi');

        return view('kasir.pembelian.index', compact('pelanggan', 'produk', 'kode_transaksi'));
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
                $produk = ItemBarang::with('stok')->findOrFail($produkId);
                $jumlah = $request->jumlah[$index];
                $totalStok = $produk->stok->sum('jumlah_stok');

                if ($totalStok < $jumlah) {
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

            // Logika diskon berdasarkan total belanja dan poin membership
            $poinDipakai = 0;
            $diskonPersen = 0;

            if ($totalBelanja >= 300000 && $pelanggan->poin_membership >= 20000) {
                $diskonPersen = 20;
                $poinDipakai = 20000;
            } elseif ($totalBelanja >= 100000 && $pelanggan->poin_membership >= 10000) {
                $diskonPersen = 10;
                $poinDipakai = 10000;
            } elseif ($totalBelanja >= 500000 && $pelanggan->poin_membership >= 30000) {
                $diskonPersen = 30;
                $poinDipakai = 30000;
            }

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
                'poin_digunakan' => $poinDipakai,
                'poin_didapat' => $poinDidapat,
                'total_akhir' => $totalAkhir,
                'uang_dibayar' => $request->uang_dibayar,
                'kembalian' => $kembalian,
                'tanggal_transaksi' => now(),
            ]);
            $laporan->save();
            $laporan->detail()->createMany($items);

            foreach ($request->produk_id as $index => $produkId) {
                $produk = ItemBarang::with('stok')->findOrFail($produkId);
                $jumlahDibeli = $request->jumlah[$index];

                foreach ($produk->stok()->orderBy('expired_date')->get() as $stok) {
                    if ($stok->jumlah_stok >= $jumlahDibeli) {
                        $stok->decrement('jumlah_stok', $jumlahDibeli);
                        break;
                    } else {
                        $jumlahDibeli -= $stok->jumlah_stok;
                        $stok->update(['jumlah_stok' => 0]);
                    }
                }
                $produk->save();
            }

            // Kurangi poin yang digunakan
            if ($poinDipakai > 0) {
                $pelanggan->decrement('poin_membership', $poinDipakai);
            }

            // Tambah poin yang didapat
            if ($poinDidapat > 0) {
                $pelanggan->tambahPoin($poinDidapat);
            }

            $this->logActivity('transaksi', $laporan, null, $laporan->toArray());
            session(['kode_transaksi' => $laporan->kode_transaksi]);

            DB::commit();
            return redirect()->route('kasir.pembelian.nota', ['kode_transaksi' => $laporan->kode_transaksi])
                ->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kasir.pembelian.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function nota($kode_transaksi)
    {
        $laporan = LaporanPenjualan::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        $detailTransaksi = DetailLaporanPenjualan::where('laporan_penjualan_id', $laporan->id)->get();

        return view('kasir.pembelian.nota', compact('laporan', 'detailTransaksi'));
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

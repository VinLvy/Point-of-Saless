<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\ItemBarang;
use App\Models\LaporanPenjualan;
use App\Models\DetailLaporanPenjualan;
use Illuminate\Support\Carbon;
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
        ]);

        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);
            $totalBelanja = 0;
            $items = [];

            foreach ($request->produk_id as $index => $produkId) {
                $produk = ItemBarang::findOrFail($produkId);
                $jumlah = $request->jumlah[$index];

                // Perbaikan logika pemilihan harga
                $hargaJual = match ($pelanggan->tipe_pelanggan) {
                    'tipe 1' => $produk->harga_jual_3, // Tipe 1 mendapatkan harga jual 3
                    'tipe 2' => $produk->harga_jual_2, // Tipe 2 mendapatkan harga jual 2
                    'tipe 3' => $produk->harga_jual_1, // Tipe 3 mendapatkan harga jual 1
                };

                $totalHarga = $hargaJual * $jumlah;
                $totalBelanja += $totalHarga;

                $items[] = new DetailLaporanPenjualan([
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga' => $hargaJual,
                    'total_harga' => $totalHarga,
                ]);
            }

            $laporan = new LaporanPenjualan([
                'pelanggan_id' => $pelanggan->id,
                'petugas_id' => auth()->id(),
                'tipe_pelanggan' => $pelanggan->tipe_pelanggan,
                'total_belanja' => $totalBelanja,
                'diskon' => 0,
                'poin_digunakan' => 0,
                'total_akhir' => $totalBelanja,
                'tanggal_transaksi' => Carbon::now(),
            ]);
            $laporan->save();
            $laporan->detail()->saveMany($items);

            DB::commit();
            return redirect()->route('kasir.pembelian.index')->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kasir.pembelian.index')->with('error', 'Terjadi kesalahan saat memproses transaksi.');
        }
    }
}

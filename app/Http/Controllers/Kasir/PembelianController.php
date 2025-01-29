<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function pembelianForm()
    {
        $pelanggan = Pelanggan::all();
        $produk = Produk::all();

        return view('kasir.pembelian.index', compact('pelanggan', 'produk'));
    }

    public function prosesPembelian(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produk,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'total_bayar' => 'required|integer|min:0',
        ]);

        $totalBelanja = 0;
        $produkDetails = [];

        foreach ($request->produk_id as $index => $produkId) {
            $produk = Produk::findOrFail($produkId);
            $jumlah = $request->jumlah[$index];
            $subtotal = $produk->harga * $jumlah;

            // Simpan detail produk untuk pembelian
            $produkDetails[] = [
                'produk_id' => $produk->id,
                'jumlah' => $jumlah,
                'harga' => $produk->harga,
                'total_harga' => $subtotal,
            ];

            $totalBelanja += $subtotal;
        }

        // Validasi agar total_bayar lebih besar atau sama dengan totalBelanja
        if ($request->total_bayar < $totalBelanja) {
            return redirect()->back()->with('error', ' Jumlah pembayaran tidak mencukupi.');
        }

        // Hitung kembalian jika ada
        $kembalian = $request->total_bayar - $totalBelanja;

        // Simpan transaksi pembelian
        $pembelian = Pembelian::create([
            'pelanggan_id' => $request->pelanggan_id,
            'total_belanja' => $totalBelanja,
            'total_bayar' => $request->total_bayar,
            'kembalian' => $kembalian >= 0 ? $kembalian : 0, // Pastikan kembalian tidak negatif
            'tanggal_pembelian' => now(),
            'petugas_id' => Auth::id(), // ID petugas
        ]);

        // Simpan detail produk yang dibeli
        foreach ($produkDetails as $detail) {
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'produk_id' => $detail['produk_id'],
                'jumlah' => $detail['jumlah'],
                'harga' => $detail['harga'],
                'total_harga' => $detail['total_harga'],
            ]);
        }

        return redirect()->route('kasir.pembelian.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}

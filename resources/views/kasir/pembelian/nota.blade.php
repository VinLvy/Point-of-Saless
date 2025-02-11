@extends('layouts.kasir')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3>Nota Transaksi</h3>
            <p>Kode Transaksi: {{ $laporan->kode_transaksi }}</p>
        </div>
        <div class="card-body">
            <p><strong>Pelanggan:</strong> {{ $laporan->pelanggan->nama_pelanggan }}</p>
            <p><strong>Tanggal:</strong> {{ $laporan->tanggal_transaksi }}</p>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama_barang }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p><strong>Total Belanja:</strong> Rp {{ number_format($laporan->total_belanja, 0, ',', '.') }}</p>
            <p><strong>Diskon:</strong> {{ $laporan->diskon }}%</p>
            <p><strong>Total Akhir:</strong> Rp {{ number_format($laporan->total_akhir, 0, ',', '.') }}</p>
            <p><strong>Uang Dibayar:</strong> Rp {{ number_format($laporan->uang_dibayar, 0, ',', '.') }}</p>
            <p><strong>Kembalian:</strong> Rp {{ number_format($laporan->kembalian, 0, ',', '.') }}</p>

            <button onclick="window.print()" class="btn btn-success">Cetak Nota</button>
            <a href="{{ route('kasir.pembelian.index') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>
</div>
@endsection

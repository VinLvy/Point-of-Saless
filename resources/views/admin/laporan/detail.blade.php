@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Detail Pembelian</h1>

    <h4>Informasi Pembelian</h4>
    <ul>
        <li>Pelanggan: {{ $detail->pembelian->pelanggan->nama_pelanggan ?? 'N/A' }}</li>
        <li>Kasir: {{ $detail->pembelian->petugas->nama_petugas ?? 'N/A' }}</li>
        <li>Tanggal: {{ $detail->created_at->format('d-m-Y H:i') }}</li>
        <li>Total Harga: {{ number_format($detail->total_harga, 0, ',', '.') }}</li>
    </ul>

    <h4>Produk</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail->produk as $produk)
            <tr>
                <td>{{ $produk['nama'] ?? 'N/A' }}</td>
                <td>{{ isset($produk['harga_satuan']) ? number_format($produk['harga_satuan'], 0, ',', '.') : 'N/A' }}</td>
                <td>{{ $produk['jumlah'] ?? 0 }}</td>
                <td>{{ isset($produk['total_harga']) ? number_format($produk['total_harga'], 0, ',', '.') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection

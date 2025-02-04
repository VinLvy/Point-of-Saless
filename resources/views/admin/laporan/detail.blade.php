@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Detail Laporan Penjualan</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informasi Transaksi</h5>
            <p><strong>Tanggal Transaksi:</strong> {{ $laporan->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Pelanggan:</strong> {{ $laporan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
            <p><strong>Total Belanja:</strong> Rp {{ number_format($laporan->total_belanja, 0, ',', '.') }}</p>
            <p><strong>Diskon:</strong> {{ number_format($laporan->diskon, 0, ',', '.') }}%</p>
            <p><strong>Total Akhir (PPN: 12%):</strong> Rp {{ number_format($laporan->total_akhir, 0, ',', '.') }}</p>
            <p><strong>Uang Dibayar:</strong> Rp {{ number_format($laporan->uang_dibayar, 0, ',', '.') }}</p>
            <p><strong>Kembalian:</strong> Rp {{ number_format($laporan->kembalian, 0, ',', '.') }}</p>
        </div>
    </div>

    <h5>Barang yang Dibeli</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan->detail as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->produk->nama_barang }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
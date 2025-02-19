@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Detail Barang: {{ $barang->nama_barang }}</h3>
    <table class="table table-bordered">
        <tr><th>Kode Barang</th><td>{{ $barang->kode_barang }}</td></tr>
        <tr><th>Kategori</th><td>{{ $barang->kategori->nama_kategori }}</td></tr>
        <tr><th>Satuan</th><td>{{ $barang->satuan }}</td></tr>
        <tr><th>Harga Beli</th><td>Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td></tr>
        <tr><th>Minimal Stok</th><td>{{ $barang->minimal_stok }}</td></tr>
    </table>

    <h4>Stok Barang</h4>
    <table class="table table-striped table-bordered">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Jumlah Stok</th>
                <th>Tanggal Pembelian</th>
                <th>Tanggal Kedaluwarsa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barang->stok as $index => $stok)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $stok->jumlah_stok }}</td>
                    <td>{{ $stok->buy_date ?? '-' }}</td>
                    <td>{{ $stok->expired_date ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
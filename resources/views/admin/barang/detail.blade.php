@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Detail Barang</h4>
                </div>
                <div class="card-body">
                    <h5 class="text-center fw-bold">{{ $barang->nama_barang }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="w-50">Kode Barang</th>
                            <td>{{ $barang->kode_barang }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $barang->kategori->nama_kategori }}</td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td>{{ $barang->satuan }}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td class="fw-bold text-primary">Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual 1</th>
                            <td class="fw-bold text-success">Rp{{ number_format($barang->harga_jual_1, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual 2</th>
                            <td class="fw-bold text-success">Rp{{ number_format($barang->harga_jual_2, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual 3</th>
                            <td class="fw-bold text-success">Rp{{ number_format($barang->harga_jual_3, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Minimal Stok</th>
                            <td class="fw-bold">{{ $barang->minimal_stok }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded mt-4">
                <div class="card-header bg-secondary text-white text-center">
                    <h5 class="mb-0">Stok Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No.</th>
                                <th>Jumlah Stok</th>
                                <th>Tanggal Pembelian</th>
                                <th>Tanggal Kedaluwarsa</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($barang->stok as $index => $stok)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-{{ $stok->jumlah_stok < $barang->minimal_stok ? 'danger' : 'dark' }}">
                                        {{ $stok->jumlah_stok }}
                                    </td>
                                    <td>{{ $stok->buy_date ?? '-' }}</td>
                                    <td>{{ $stok->expired_date ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

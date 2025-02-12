@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">📄 Detail Laporan Transaksi</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">🛒 Informasi Transaksi</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Kode Transaksi:</strong> <span class="badge bg-primary">{{ $laporan->kode_transaksi }}</span></p>
                    <p><strong>Tanggal Transaksi:</strong> {{ $laporan->created_at->format('d-m-Y H:i') }}</p>
                    <p><strong>Pelanggan:</strong> {{ $laporan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                    <p><strong>Petugas:</strong> {{ $laporan->petugas->nama_petugas }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Belanja:</strong> <span class="text-success fw-bold">Rp {{ number_format($laporan->total_belanja, 0, ',', '.') }}</span></p>
                    <p><strong>Diskon:</strong> <span class="text-danger fw-bold">-{{ number_format($laporan->diskon, 0, ',', '.') }}%</span></p>
                    <p><strong>Total Akhir <span class="badge bg-warning">PPN 12%</span>:</strong> <span class="text-success fw-bold">Rp {{ number_format($laporan->total_akhir, 0, ',', '.') }}</span></p>
                    <p><strong>Uang Dibayar:</strong> <span class="text-primary fw-bold">Rp {{ number_format($laporan->uang_dibayar, 0, ',', '.') }}</span></p>
                    <p><strong>Kembalian:</strong> <span class="text-success fw-bold">Rp {{ number_format($laporan->kembalian, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">🛍️ Barang yang Dibeli</h5>
    <div class="table-responsive">
        <table class="table table-striped table-hover text-center align-middle">
            <thead class="table-dark">
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
                    <td>{{ $detail->itemBarang->nama_barang }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-success fw-bold">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary px-4 py-2">
            Kembali ke Laporan
        </a>
    </div>
</div>
@endsection

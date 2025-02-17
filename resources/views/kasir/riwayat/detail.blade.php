@extends('layouts.kasir')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-4 text-center">Detail Laporan Transaksi</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>

    <div class="card shadow-sm border-primary mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4 text-primary">Informasi Transaksi</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Kode Transaksi:</strong> <span class="badge bg-primary">{{ $laporan->kode_transaksi }}</span></p>
                    <p><strong>Tanggal Transaksi:</strong> {{ $laporan->created_at->format('d-m-Y H:i') }}</p>
                    <p><strong>Pelanggan:</strong> {{ $laporan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                    <p><strong>Petugas:</strong> {{ $laporan->petugas->nama_petugas }}</p>
                    <p><strong>Poin Digunakan:</strong> {{ $laporan->poin_digunakan }}</p>
                    <p><strong>Poin Didapat:</strong> {{ $laporan->poin_didapat }}</p>
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

    <h5 class="mb-3 text-primary">Barang yang Dibeli</h5>
    <div class="table-responsive rounded-3">
        <table class="table table-bordered table-striped table-hover text-center align-middle">
            <thead class="table-primary">
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

    <div class="text-center mt-4 d-print-none">
        <a href="{{ route('kasir.riwayat.index') }}" class="btn btn-secondary px-4 py-2">
            Kembali ke Laporan
        </a>
    </div>
</div>

<!-- CSS Khusus untuk Mode Cetak -->
<style>
    @media print {
        body {
            font-size: 12px;
        }
        .btn, .d-print-none {
            display: none !important;
        }
    }
</style>
@endsection

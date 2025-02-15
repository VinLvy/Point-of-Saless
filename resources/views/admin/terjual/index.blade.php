@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-receipt"></i> Laporan Barang Terjual</h4>
            <button onclick="window.print()" class="btn btn-light btn-sm d-print-none">
                <i class="bi bi-printer"></i> Print Laporan
            </button>
        </div>
        <div class="card-body">
            <!-- Form Filter Pencarian (Disembunyikan saat Print) -->
            <form method="GET" action="{{ route('admin.terjual.index') }}" class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5 d-print-none">
                        <label for="search" class="form-label fw-bold">Cari Transaksi / Barang</label>
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Masukkan kode transaksi atau nama barang..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-bold d-print-none">Pilih Tanggal</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date') ? request('start_date') : '' }}" placeholder="DD/MM/YYYY">
                    </div>
                    <div class="col-md-3 d-print-none">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Data Penjualan -->
            <div class="table-responsive" style="border-radius: 8px;">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center text-white">
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Terjual</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalJumlah = 0;
                            $totalHarga = 0;
                        @endphp
                        
                        @forelse ($laporan as $item)
                            @foreach ($item->detail as $detail)
                                @php
                                    $totalJumlah += $detail->jumlah;
                                    $totalHarga += $detail->total_harga;
                                @endphp
                                <tr>
                                    <td class="fw-bold text-center">{{ $item->kode_transaksi }}</td>
                                    <td>{{ $detail->itemBarang->nama_barang ?? 'Tidak Diketahui' }}</td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                    <td class="text-end">Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data penjualan yang sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>  
                    <tfoot class="table-dark text-white">
                        <tr>
                            <td colspan="2" class="text-center fw-bold">Total</td>
                            <td class="text-center fw-bold">{{ $totalJumlah }}</td>
                            <td class="text-end fw-bold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination (Disembunyikan Saat Print) -->
            <div class="d-flex justify-content-center mt-3 d-print-none">
                {{ $laporan->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk menyembunyikan tombol print & search bar saat mencetak -->
<style>
    @media print {
        .d-print-none {
            display: none !important;
        }
    }
</style>
@endsection

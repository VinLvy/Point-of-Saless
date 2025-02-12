@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-receipt"></i> Laporan Barang Terjual</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.terjual.index') }}" class="mb-3 d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode transaksi atau nama barang..." value="{{ request('search') }}">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->subWeek()->toDateString()) }}">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->toDateString()) }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
            </form>

            <div class="table-responsive">
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
                                    <td class="fw-bold">{{ $item->kode_transaksi }}</td>
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
        </div>
    </div>
</div>
@endsection

@extends('layouts.kasir')

@section('content')
<div class="container mt-3">
    <h1 class="mb-0"><i class="bi bi-receipt"></i> Riwayat Penjualan</h1>

    <form method="GET" action="{{ route('kasir.riwayat.index') }}">
        <div class="row mb-3">
            <div class="col">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ date('Y-m-d', strtotime($startDate)) }}">
            </div>
            <div class="col">
                <label for="end_date">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ date('Y-m-d', strtotime($endDate)) }}">
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive rounded-3" style="overflow: hidden;">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>No.</th>
                    <th>Kode Transaksi</th>
                    <th>Petugas</th>
                    <th>Pelanggan</th>
                    <th>Total Belanja</th>
                    <th>Diskon</th>
                    <th>Total Akhir (PPN: 12%)</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayat as $index => $item)
                <tr>
                    <td>{{ $riwayat->firstItem() + $index }}</td>
                    <td>{{ $item->kode_transaksi ?? 'N/A' }}</td>
                    <td>{{ $item->petugas->nama_petugas }}</td>
                    <td>{{ $item->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                    <td>{{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->diskon, 0, ',', '.') }}%</td>
                    <td>{{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <a href="{{ route('kasir.riwayat.show', $item->kode_transaksi) }}" class="btn btn-sm btn-info">
                            Lihat Nota
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>    
    <div class="d-flex justify-content-center mt-3 d-print-none">
        {{ $riwayat->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection

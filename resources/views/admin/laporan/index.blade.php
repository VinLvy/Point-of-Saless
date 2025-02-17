@extends('layouts.admin')

@section('content')
<div class="container mt-3">
    <h1 class="mb-0"><i class="bi bi-receipt"></i> Riwayat Penjualan</h1>

    <form method="GET" action="{{ route('admin.laporan.index') }}" class="d-print-none">
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

    <button onclick="window.print()" class="btn btn-success mb-3 d-print-none">
        <i class="bi bi-printer"></i> Cetak Riwayat
    </button>

    <div class="table-responsive rounded-3">
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
                    <th class="d-print-none">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_transaksi ?? 'N/A' }}</td>
                    <td>{{ $item->petugas->nama_petugas }}</td>
                    <td>{{ $item->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                    <td>{{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->diskon, 0, ',', '.') }}%</td>
                    <td>{{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                    <td class="d-print-none">
                        <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('admin.laporan.nota', $item->kode_transaksi) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-receipt"></i> Nota
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>    
</div>

<style>
    @media print {
        .d-print-none {
            display: none !important;
        }
    table {
        width: 100% !important;
        font-size: 12px;
    }
    th, td {
        padding: 4px !important;
        word-wrap: break-word;
    }
    body {
        margin: 0;
        padding: 0;
    }
}

</style>
@endsection

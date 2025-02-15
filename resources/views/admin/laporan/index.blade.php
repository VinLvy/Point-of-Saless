@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Laporan Transaksi</h1>

    <div class="card shadow-sm p-4 mb-4">
        <form method="GET" action="{{ route('admin.laporan.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="{{ date('Y-m-d', strtotime($startDate)) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="{{ date('Y-m-d', strtotime($endDate)) }}">
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Kode Transaksi</label>
                    <input type="text" class="form-control" id="search" name="search" 
                        placeholder="Masukkan kode transaksi" value="{{ request('search') }}">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary px-4">Cari</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary px-4 ms-2">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive" style="border-radius: 8px;">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kode Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Total Belanja</th>
                    <th>Diskon</th>
                    <th>Total Akhir (PPN: 12%)</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge bg-primary">{{ $item->kode_transaksi ?? 'N/A' }}</span></td>
                    <td>{{ $item->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                    <td class="text-success fw-bold">Rp {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                    <td class="text-danger fw-bold">{{ number_format($item->diskon, 0, ',', '.') }}%</td>
                    <td class="text-success fw-bold">Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

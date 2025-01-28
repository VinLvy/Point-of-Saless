@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Laporan Pembelian</h1>

    <form method="GET" action="{{ route('admin.laporan.index') }}">
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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $item->pembelian->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                <td>{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

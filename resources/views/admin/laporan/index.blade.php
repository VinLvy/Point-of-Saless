@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Laporan Pembelian</h1>
    <form method="GET" action="{{ route('admin.laporan.index') }}">
        <div class="row">
            <div class="col-md-4">
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
            </div>
            <div class="col-md-4">
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $item)
            <tr>
                <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                <td>{{ $item->produk->nama_produk }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->total_harga }}</td>
                <td>{{ $item->created_at }}</td>
                <td>
                    <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-info">Lihat Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
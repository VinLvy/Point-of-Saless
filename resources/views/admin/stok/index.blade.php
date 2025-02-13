@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Stok Barang</h2>
    <a href="{{ route('admin.stok.create') }}" class="btn btn-success mb-3">Tambah Stok</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah Stok</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Tanggal Pembelian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stok as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->itemBarang->nama_barang }}</td>
                <td>{{ $item->jumlah_stok }}</td>
                <td>{{ $item->expired_date }}</td>
                <td>{{ $item->buy_date }}</td>
                <td>
                    <form action="{{ route('stok.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Stok Barang</h2>

    {{-- Notifikasi Error dengan SweetAlert2 --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `
                    <ul style='text-align: left;'>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
            });
        </script>
    @endif

    <form action="{{ route('admin.stok.update', $stok->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="item_id" class="form-label">Nama Barang</label>
            <select name="item_id" id="item_id" class="form-control" required>
                @foreach($items as $barang)
                    <option value="{{ $barang->id }}" {{ $stok->item_id == $barang->id ? 'selected' : '' }}>
                        {{ $barang->nama_barang }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah_stok" class="form-label">Jumlah Stok</label>
            <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" value="{{ $stok->jumlah_stok }}" required>
        </div>
        <div class="mb-3">
            <label for="expired_date" class="form-label">Tanggal Kedaluwarsa</label>
            <input type="date" name="expired_date" id="expired_date" class="form-control" value="{{ $stok->expired_date }}" required>
        </div>
        <div class="mb-3">
            <label for="buy_date" class="form-label">Tanggal Pembelian</label>
            <input type="date" name="buy_date" id="buy_date" class="form-control" value="{{ $stok->buy_date }}" required>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update Stok</button>
        </div>
    </form>
</div>
@endsection

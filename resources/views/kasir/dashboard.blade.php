@extends('layouts.kasir')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Selamat Datang, {{ $petugas }}!</h1>

    <div class="row">
        <!-- Card Jumlah Barang -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Jumlah Barang</h5>
                    <h3 class="text-primary fw-bold">{{ $jumlah_barang }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi Barang Kurang Stok -->
    @if ($barang_kurang_stok->count() > 0)
        <div class="alert alert-warning mt-4">
            <h5><i class="bi bi-exclamation-triangle-fill"></i> Barang dengan Stok Rendah</h5>
            <ul>
                @foreach ($barang_kurang_stok as $barang)
                    <li>{{ $barang->nama_barang }} - Stok: {{ $barang->stok }} (Minimal: {{ $barang->minimal_stok }})</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection

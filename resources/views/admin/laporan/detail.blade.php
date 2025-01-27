@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Detail Pembelian</h1>
    <p><strong>Pelanggan:</strong> {{ $detail->pelanggan->nama_pelanggan }}</p>
    <p><strong>Produk:</strong> {{ $detail->produk->nama_produk }}</p>
    <p><strong>Jumlah:</strong> {{ $detail->jumlah }}</p>
    <p><strong>Total Harga:</strong> {{ $detail->total_harga }}</p>
    <p><strong>Tanggal:</strong> {{ $detail->created_at }}</p>
    <a href="#" class="btn btn-success" onclick="window.print()">Print Nota</a>
</div>
@endsection
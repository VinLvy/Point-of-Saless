@extends('layouts.kasir')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="nota-title">Nota Transaksi</h3>
            <p class="nota-text">Kode Transaksi: {{ $laporan->kode_transaksi }}</p>
        </div>
        <div class="card-body nota-body">
            <p><strong>Pelanggan:</strong> {{ $laporan->pelanggan->nama_pelanggan }}</p>
            <p><strong>Tanggal:</strong> {{ $laporan->tanggal_transaksi }}</p>

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama_barang }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p><strong>Total Belanja:</strong> Rp {{ number_format($laporan->total_belanja, 0, ',', '.') }}</p>
            <p><strong>Diskon:</strong> {{ $laporan->diskon }}%</p>
            <p><strong>Total Akhir (PPN 12%):</strong> Rp {{ number_format($laporan->total_akhir, 0, ',', '.') }}</p>
            <p><strong>Uang Dibayar:</strong> Rp {{ number_format($laporan->uang_dibayar, 0, ',', '.') }}</p>
            <p><strong>Kembalian:</strong> Rp {{ number_format($laporan->kembalian, 0, ',', '.') }}</p>
        </div>

        <div class="card-footer d-flex justify-content-between no-print">
            <a href="{{ route('kasir.pembelian.index') }}" class="btn btn-primary">Kembali</a>
            <button onclick="window.print()" class="btn btn-success">Cetak Nota</button>
        </div>
    </div>
</div>

<style>
    .nota-title {
        font-family: "Courier New", Courier, monospace;
        font-weight: bold;
        text-transform: uppercase;
    }
    .nota-text {
        font-family: "Courier New", Courier, monospace;
        font-size: 14px;
    }
    .nota-body {
        font-family: "Courier New", Courier, monospace;
        font-size: 14px;
    }
    .table th, .table td {
        font-family: "Courier New", Courier, monospace;
        font-size: 14px;
    }
    .no-print {
        display: flex;
        justify-content: space-between;
        padding: 10px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .container {
            width: 100%;
            max-width: 100%;
        }
        .card {
            border: none;
            box-shadow: none;
        }
    }
</style>
@endsection

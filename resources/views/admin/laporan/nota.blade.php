@extends('layouts.admin')

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
            <hr>

            @foreach ($detailTransaksi as $detail)
                <div class="nota-item">
                    <span class="nama-produk">{{ $detail->itemBarang->nama_barang }}</span>
                    <span class="harga-satuan">Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                    <span class="jumlah">x{{ $detail->jumlah }} {{ $detail->itemBarang->satuan }}</span>
                    <span class="total-harga">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <hr>

            <p><strong>Poin Yang Digunakan:</strong> {{ $laporan->poin_digunakan }}</p>
            <p><strong>Poin Yang Didapat:</strong> {{ $laporan->poin_didapat }}</p>
            <p><strong>Total Belanja:</strong> Rp {{ number_format($laporan->total_belanja, 0, ',', '.') }}</p>
            <p><strong>Diskon:</strong> {{ $laporan->diskon }}%</p>
            <p><strong>Total Akhir (PPN 12%):</strong> Rp {{ number_format($laporan->total_akhir, 0, ',', '.') }}</p>
            <p><strong>Uang Dibayar:</strong> Rp {{ number_format($laporan->uang_dibayar, 0, ',', '.') }}</p>
            <p><strong>Kembalian:</strong> Rp {{ number_format($laporan->kembalian, 0, ',', '.') }}</p>
        </div>

        <div class="card-footer no-print">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-primary">Kembali</a>
            <button onclick="window.print()" class="btn btn-success">Cetak Nota</button>
        </div>
    </div>
</div>

<style>
    .nota-title, .nota-text, .nota-body {
        font-family: "Courier New", Courier, monospace;
    }
    .nota-title {
        font-weight: bold;
        text-transform: uppercase;
    }
    .nota-text, .nota-body {
        font-size: 14px;
    }

    .nota-item {
        display: flex;
        justify-content: space-between;
        font-family: "Courier New", Courier, monospace;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .nama-produk {
        flex: 3;
        text-align: left;
    }
    .harga-satuan {
        flex: 1;
        text-align: center;
    }
    .jumlah {
        flex: 1;
        text-align: center;
    }
    .total-harga {
        flex: 2;
        text-align: right;
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

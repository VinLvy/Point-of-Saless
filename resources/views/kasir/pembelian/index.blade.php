@extends('layouts.kasir')

@section('content')
<div class="container">
    <h2 class="mb-4">Transaksi Pembelian</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('kasir.pembelian.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="pelanggan_id" class="form-label">Pilih Pelanggan</label>
            <select name="pelanggan_id" id="pelanggan_id" class="form-control" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                @endforeach
            </select>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="produk-list">
                <tr>
                    <td>
                        <select name="produk_id[]" class="form-control produk-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $pr)
                                <option value="{{ $pr->id }}" data-harga="{{ $pr->harga_jual_1 }}">{{ $pr->nama_barang }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" required></td>
                    <td class="harga">0</td>
                    <td class="total">0</td>
                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="tambah-produk" class="btn btn-primary">Tambah Produk</button>

        <div class="mt-3">
            <label for="total_bayar" class="form-label">Total Bayar</label>
            <input type="number" name="total_bayar" id="total_bayar" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success mt-3">Simpan Transaksi</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function updateTotal() {
        let totalBayar = 0;
        document.querySelectorAll("#produk-list tr").forEach(function(row) {
            let jumlah = row.querySelector(".jumlah").value || 0;
            let harga = row.querySelector(".produk-select").selectedOptions[0].dataset.harga || 0;
            let total = jumlah * harga;
            row.querySelector(".harga").innerText = harga;
            row.querySelector(".total").innerText = total;
            totalBayar += total;
        });
        document.querySelector("#total_bayar").value = totalBayar;
    }
    
    document.querySelector("#tambah-produk").addEventListener("click", function() {
        let row = document.querySelector("#produk-list tr").cloneNode(true);
        row.querySelector(".jumlah").value = "";
        row.querySelector(".harga").innerText = "0";
        row.querySelector(".total").innerText = "0";
        document.querySelector("#produk-list").appendChild(row);
    });
    
    document.addEventListener("change", function(event) {
        if (event.target.matches(".produk-select, .jumlah")) {
            updateTotal();
        }
    });
    
    document.addEventListener("click", function(event) {
        if (event.target.matches(".remove-row")) {
            event.target.closest("tr").remove();
            updateTotal();
        }
    });
});
</script>
@endsection

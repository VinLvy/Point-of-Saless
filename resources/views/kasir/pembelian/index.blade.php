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

        <input type="hidden" id="harga_produk_json" 
               value="{{ json_encode($produk->mapWithKeys(fn($p) => [$p->id => [
                   'tipe_1' => $p->harga_jual_3, 
                   'tipe_2' => $p->harga_jual_2, 
                   'tipe_3' => $p->harga_jual_1
               ]])) }}">
        
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
            <tbody id="produk-list"></tbody>
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
        let hargaProduk = JSON.parse(document.querySelector("#harga_produk_json").value);

        function updateTotal() {
            let totalBayar = 0;
            document.querySelectorAll("#produk-list tr").forEach(function(row) {
                let jumlah = parseFloat(row.querySelector(".jumlah").value) || 0;
                let harga = parseFloat(row.querySelector(".harga").dataset.harga) || 0;
                let total = jumlah * harga;
    
                row.querySelector(".harga").innerText = harga.toLocaleString();
                row.querySelector(".total").innerText = total.toLocaleString();
                totalBayar += total;
            });
            document.querySelector("#total_bayar").value = totalBayar;
        }

        function tambahProduk() {
            let row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <select name="produk_id[]" class="form-control produk-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produk as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->nama_barang }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" required></td>
                <td class="harga" data-harga="0">0</td>
                <td class="total">0</td>
                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
            `;
            document.querySelector("#produk-list").appendChild(row);
        }

        document.querySelector("#tambah-produk").addEventListener("click", tambahProduk);

        document.addEventListener("change", function(event) {
            if (event.target.matches(".produk-select")) {
                let row = event.target.closest("tr");
                let produkId = event.target.value;
                let pelangganId = document.querySelector("#pelanggan_id").value;
                let tipeHarga = pelangganId ? "tipe_" + pelangganId : "tipe_1";
                let harga = hargaProduk[produkId]?.[tipeHarga] || 0;
                
                row.querySelector(".harga").dataset.harga = harga;
                updateTotal();
            }
            if (event.target.matches(".jumlah")) {
                updateTotal();
            }
        });

        document.addEventListener("click", function(event) {
            if (event.target.matches(".remove-row")) {
                event.target.closest("tr").remove();
                updateTotal();
            }
        });
        
        document.querySelector("#pelanggan_id").addEventListener("change", function () {
            document.querySelector("#produk-list").innerHTML = ""; 
            updateTotal();
        });
    });
</script>
@endsection

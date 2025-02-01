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
                    <option value="{{ $p->id }}">{{ $p->nama_pelanggan }} (Pelanggan {{ $p->tipe_pelanggan }})</option>
                @endforeach
            </select>
        </div>

        <input type="hidden" id="harga_produk_json" 
               value="{{ json_encode($produk->mapWithKeys(fn($p) => [$p->id => [
                   'stok' => $p->stok,
                   'tipe_1' => $p->harga_jual_3, 
                   'tipe_2' => $p->harga_jual_2, 
                   'tipe_3' => $p->harga_jual_1
               ]])) }}">

        <table class="table table-bordered table-fixed">
            <thead>
                <tr>
                    <th style="width: 30%;">Produk</th>
                    <th style="width: 15%;">Stok Tersedia</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 15%;">Total</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody id="produk-list"></tbody>
        </table>

        <button type="button" id="tambah-produk" class="btn btn-primary">Tambah Produk</button>
        
        <div class="mt-3">
            <label for="total_bayar_display" class="form-label">Total Harga</label>
            <span id="total_bayar_display" class="form-control">Rp 0</span>
        </div>

        <div class="mt-3">
            <label for="diskon" class="form-label">Diskon (%) (Opsional)</label>
            <div class="input-group">
                <input type="text" name="diskon" id="diskon" class="form-control" min="0" max="100" disabled>
                <button type="button" id="konfirmasi_diskon" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
        
        <div class="mt-3">
            <label for="total_diskon_display" class="form-label">Total Diskon</label>
            <span id="total_diskon_display" class="form-control">Rp 0</span>
        </div>
        
        <div class="mt-3">
            <label for="total_akhir_display" class="form-label">Total Akhir (PPN 12%)</label>
            <span id="total_akhir_display" class="form-control">Rp 0</span>
        </div>
        
        <input type="hidden" name="total_bayar" id="total_bayar">
        <input type="hidden" name="total_diskon" id="total_diskon">
        <input type="hidden" name="total_akhir" id="total_akhir">        

        <button type="submit" class="btn btn-success mt-3">Simpan Transaksi</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let hargaProduk = JSON.parse(document.querySelector("#harga_produk_json").value);
    let diskonInput = document.querySelector("#diskon");
    let konfirmasiDiskonBtn = document.querySelector("#konfirmasi_diskon");

    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(angka);
    }

    function checkProduk() {
        let adaProduk = document.querySelector("#produk-list tr") !== null;
        diskonInput.disabled = !adaProduk;
    }

    function updateTotal() {
        let totalBayar = 0;
        document.querySelectorAll("#produk-list tr").forEach(function(row) {
            let jumlah = parseFloat(row.querySelector(".jumlah").value) || 0;
            let harga = parseFloat(row.querySelector(".harga").dataset.harga) || 0;
            let total = jumlah * harga;

            row.querySelector(".harga").innerText = formatRupiah(harga);
            row.querySelector(".total").innerText = formatRupiah(total);
            totalBayar += total;
        });

        let diskonPersen = parseFloat(document.querySelector("#diskon").value) || 0;
        let diskonNominal = (diskonPersen / 100) * totalBayar;
        let totalSetelahDiskon = totalBayar - diskonNominal;

        let totalAkhir = totalSetelahDiskon * 1.12;

        document.querySelector("#total_bayar_display").innerText = formatRupiah(totalBayar);
        document.querySelector("#total_diskon_display").innerText = formatRupiah(diskonNominal);
        document.querySelector("#total_akhir_display").innerText = formatRupiah(totalAkhir);

        document.querySelector("#total_bayar").value = totalBayar;
        document.querySelector("#total_diskon").value = diskonNominal;
        document.querySelector("#total_akhir").value = totalAkhir;
    }

    konfirmasiDiskonBtn.addEventListener("click", function () {
        let diskonPersen = parseFloat(diskonInput.value) || 0;

        if (diskonPersen < 0 || diskonPersen > 100) {
            alert("Diskon harus antara 0% - 100%");
            return;
        }
            updateTotal();     
    });

    document.addEventListener("change", function(event) {
        if (event.target.matches(".produk-select")) {
            let row = event.target.closest("tr");
            let produkId = event.target.value;
            let pelangganId = document.querySelector("#pelanggan_id").value;
            let tipeHarga = pelangganId ? "tipe_" + pelangganId : "tipe_1";
            let harga = hargaProduk[produkId]?.[tipeHarga] || 0;
            let stok = hargaProduk[produkId]?.stok || 0;

            row.querySelector(".harga").dataset.harga = harga;
            row.querySelector(".stok-terpakai").innerText = stok;
            row.querySelector(".jumlah").setAttribute("max", stok);
            updateTotal();
        }

        if (event.target.matches(".jumlah")) {
            let row = event.target.closest("tr");
            let jumlahInput = event.target;
            let stokTersedia = parseInt(row.querySelector(".stok-terpakai").innerText) || 0;
            let jumlah = parseInt(jumlahInput.value) || 0;

            if (jumlah > stokTersedia) {
                jumlahInput.value = stokTersedia;
            }
            updateTotal();
        }
    });

    document.addEventListener("click", function(event) {
        if (event.target.matches(".remove-row")) {
            event.target.closest("tr").remove();
            updateTotal();
            checkProduk();
        }
    });

    document.querySelector("#tambah-produk").addEventListener("click", function() {
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>
                <select name="produk_id[]" class="form-control produk-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produk as $pr)
                        <option value="{{ $pr->id }}" data-stok="{{ $pr->stok }}">
                            {{ $pr->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="stok-terpakai">-</td>
            <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" required></td>
            <td class="harga" data-harga="0">0</td>
            <td class="total">0</td>
            <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
        `;
        document.querySelector("#produk-list").appendChild(row);
        checkProduk();
    });

    document.querySelector("#pelanggan_id").addEventListener("change", function () {
        document.querySelector("#produk-list").innerHTML = "";
        updateTotal();
        checkProduk();
    });

    checkProduk();
});

</script>
@endsection

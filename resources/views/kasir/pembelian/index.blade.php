@extends('layouts.kasir')

@section('content')
<div class="container">
    <h2 class="mb-4">Transaksi Pembelian</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <form action="{{ route('kasir.pembelian.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="pelanggan_id" class="form-label">Pilih Pelanggan</label>
            <select name="pelanggan_id" id="pelanggan_id" class="form-control select2" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}" data-tipe="{{ str_replace(' ', '_', strtolower($p->tipe_pelanggan)) }}">
                        {{ $p->nama_pelanggan }} (Pelanggan {{ $p->tipe_pelanggan }})
                    </option>
                @endforeach
            </select>
        </div>
        
        <input type="hidden" id="harga_produk_json" 
               value="{{ json_encode($produk->mapWithKeys(fn($p) => [$p->id => [
                   'stok' => $p->stok,
                   'tipe_1' => $p->harga_jual_1, 
                   'tipe_2' => $p->harga_jual_2, 
                   'tipe_3' => $p->harga_jual_3
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

        <div class="mt-3">
            <label for="uang_dibayar" class="form-label">Uang Dibayarkan</label>
            <input type="number" name="uang_dibayar" id="uang_dibayar" class="form-control" min="0" required>
        </div>

        <div class="mt-3">
            <label for="kembalian_display" class="form-label">Kembalian</label>
            <span id="kembalian_display" class="form-control">Rp 0</span>
        </div>
        
        <input type="hidden" name="total_bayar" id="total_bayar">
        <input type="hidden" name="total_diskon" id="total_diskon">
        <input type="hidden" name="total_akhir" id="total_akhir">        

        <div class="mb-3">
            <small id="error-uang-dibayar" class="text-danger d-block mt-2"></small>
        </div>
        
        <!-- Tombol untuk membuka modal -->
        <button id="proses-transaksi" class="btn btn-success mt-2 w-100" disabled data-bs-toggle="modal" data-bs-target="#konfirmasiModal">
            Proses Transaksi
        </button>
               
    </form>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Konfirmasi Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin memproses transaksi ini?</p>
                <p><strong>Total Akhir:</strong> <span id="modal_total_akhir">Rp 0</span></p>
                <p><strong>Uang Dibayarkan:</strong> <span id="modal_uang_dibayar">Rp 0</span></p>
                <p><strong>Kembalian:</strong> <span id="modal_kembalian">Rp 0</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="konfirmasiProses">Ya, Proses</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#pelanggan_id').select2({
            placeholder: "-- Pilih Pelanggan --",
            allowClear: true,
            width: '100%'
        });
    });

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

    document.querySelector("#uang_dibayar").addEventListener("input", function () {
        let uangDibayar = parseFloat(this.value) || 0;
        let totalAkhir = parseFloat(document.querySelector("#total_akhir").value) || 0;
        let kembalian = uangDibayar - totalAkhir;

    document.querySelector("#kembalian_display").innerText = formatRupiah(Math.max(kembalian, 0));

        let submitButton = document.querySelector("#proses-transaksi");
        
        if (uangDibayar < totalAkhir) {
            document.querySelector("#uang_dibayar").classList.add("is-invalid");
            document.querySelector("#error-uang-dibayar").innerText = "Uang yang dibayarkan tidak mencukupi!";
            submitButton.setAttribute("disabled", "disabled");
        } else {
            document.querySelector("#uang_dibayar").classList.remove("is-invalid");
            document.querySelector("#error-uang-dibayar").innerText = "";
            submitButton.removeAttribute("disabled");
        }
    });


    konfirmasiDiskonBtn.addEventListener("click", function () {
        let adaProduk = document.querySelector("#produk-list tr") !== null;
        if (!adaProduk) {
            alert("Tambahkan produk terlebih dahulu sebelum memberikan diskon!");
            return;
        }

        let diskonPersen = parseFloat(diskonInput.value) || 0;
        if (diskonPersen < 0 || diskonPersen > 100) {
            alert("Diskon harus antara 0% - 100%");
            return;
        }
        updateTotal();
    });

    document.addEventListener("change", function(event) {
        let pelangganSelect = document.querySelector("#pelanggan_id");
        let pelangganTipe = pelangganSelect.selectedOptions[0]?.dataset.tipe || "tipe_3";
        let hargaProduk = JSON.parse(document.querySelector("#harga_produk_json").value);

        // Jika yang diubah adalah produk yang dipilih
        if (event.target.matches(".produk-select")) {
            let row = event.target.closest("tr");
            let produkId = event.target.value;

            let tipeHarga = pelangganTipe;
            let harga = hargaProduk[produkId]?.[tipeHarga] || 0;
            let stok = hargaProduk[produkId]?.stok || 0;

            row.querySelector(".harga").dataset.harga = harga;
            row.querySelector(".stok-terpakai").innerText = stok;
            row.querySelector(".jumlah").setAttribute("max", stok);
            updateTotal();
        }

        // Jika pelanggan berubah, update semua harga produk yang dipilih
        if (event.target.matches("#pelanggan_id")) {
            document.querySelectorAll(".produk-select").forEach(select => {
                let row = select.closest("tr");
                let produkId = select.value;

                let tipeHarga = pelangganTipe;
                let harga = hargaProduk[produkId]?.[tipeHarga] || 0;
                let stok = hargaProduk[produkId]?.stok || 0;

                row.querySelector(".harga").dataset.harga = harga;
                row.querySelector(".stok-terpakai").innerText = stok;
                row.querySelector(".jumlah").setAttribute("max", stok);
            });

            updateTotal();
        }

        if (event.target.matches(".jumlah")) {
            let row = event.target.closest("tr");
            let jumlahInput = event.target;
            let stokTersedia = parseInt(row.querySelector(".stok-terpakai").innerText) || 0;
            let jumlah = parseInt(jumlahInput.value) || 0;

            if (jumlah > stokTersedia) {
                alert("Stok barang tidak cukup!");
                jumlahInput.value = 0;
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
        document.querySelector("#diskon").value = "";
        document.querySelector("#diskon").disabled = true;
        document.querySelector("#total_diskon_display").innerText = "";
        document.querySelector("#total_akhir_display").innerText = "";
        document.querySelector("#total_diskon").value = 0; 
        document.querySelector("#total_akhir").value = 0;
        document.querySelector("#uang_dibayar").value = "";
        document.querySelector("#kembalian_display").innerText = "Rp 0";
        updateTotal();
        checkProduk();
    });

    checkProduk();

    let prosesTransaksiBtn = document.querySelector("#proses-transaksi");
    let modalTotalAkhir = document.querySelector("#modal_total_akhir");
    let modalUangDibayar = document.querySelector("#modal_uang_dibayar");
    let modalKembalian = document.querySelector("#modal_kembalian");

    prosesTransaksiBtn.addEventListener("click", function () {
        modalTotalAkhir.innerText = document.querySelector("#total_akhir_display").innerText;
        modalUangDibayar.innerText = document.querySelector("#uang_dibayar").value ? formatRupiah(document.querySelector("#uang_dibayar").value) : "Rp 0";
        modalKembalian.innerText = document.querySelector("#kembalian_display").innerText;
    });
});

</script>
@endsection

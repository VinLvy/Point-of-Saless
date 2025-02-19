@extends('layouts.kasir')

@section('content')
<div class="container">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-center text-primary">Transaksi Pembelian</h2>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @elseif(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        
        <form id="form-transaksi" action="{{ route('kasir.pembelian.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="pelanggan_id" class="form-label fw-bold">Pilih Pelanggan</label>
                <select name="pelanggan_id" id="pelanggan_id" class="form-select select2" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}" 
                            data-tipe="{{ str_replace(' ', '_', strtolower($p->tipe_pelanggan)) }}" 
                            data-poin="{{ $p->poin_membership }}">
                            {{ $p->nama_pelanggan }} (Pelanggan {{ $p->tipe_pelanggan }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Poin Membership</label>
                <input type="text" id="poin_pelanggan" class="form-control bg-light" value="0" readonly>
            </div>
            
            <input type="hidden" id="harga_produk_json" 
                   value="{{ json_encode($produk->mapWithKeys(fn($p) => [$p->id => [
                       'stok' => $p->stok,
                       'satuan' => $p->satuan,
                       'tipe_1' => $p->harga_jual_1, 
                       'tipe_2' => $p->harga_jual_2, 
                       'tipe_3' => $p->harga_jual_3
                   ]])) }}">
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="produk-list"></tbody>
                </table>
            </div>

            <button type="button" id="tambah-produk" class="btn btn-primary mt-2">Tambah Produk</button>

            <div class="mt-3">
                <label for="total_bayar_display" class="form-label fw-bold">Total Harga</label>
                <span id="total_bayar_display" class="form-control bg-light">Rp 0</span>
            </div>
            
            <div class="mt-3">
                <label for="diskon" class="form-label fw-bold">Diskon (%)</label>
                <select name="diskon" id="diskon" class="form-select" disabled>
                    <option value="0">Pilih Diskon</option>
                </select>
            </div>
            
            <div class="mt-3">
                <label for="total_diskon_display" class="form-label fw-bold">Total Diskon</label>
                <span id="total_diskon_display" class="form-control bg-light">Rp 0</span>
            </div>
            
            <div class="mt-3">
                <label for="total_akhir_display" class="form-label fw-bold">Total Akhir (PPN 12%)</label>
                <span id="total_akhir_display" class="form-control bg-light">Rp 0</span>
            </div>

            <div class="mt-3">
                <label for="uang_dibayar" class="form-label fw-bold">Uang Dibayarkan</label>
                <input type="number" name="uang_dibayar" id="uang_dibayar" class="form-control" min="0" required>
            </div>
            
            <div class="mt-3">
                <label for="kembalian_display" class="form-label fw-bold">Kembalian</label>
                <span id="kembalian_display" class="form-control bg-light">Rp 0</span>
            </div>
            
            <input type="hidden" name="total_bayar" id="total_bayar">
            <input type="hidden" name="total_diskon" id="total_diskon">
            <input type="hidden" name="total_akhir" id="total_akhir">
            
            <small id="error-uang-dibayar" class="text-danger d-block mt-2"></small>
            
            <button id="proses-transaksi" type="button" class="btn btn-success mt-3 w-100" disabled data-bs-toggle="modal" data-bs-target="#konfirmasiModal">
                Proses Transaksi
            </button>
        </form>
    </div>
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
                <button type="button" class="btn btn-primary" id="konfirmasiProses" data-url="{{ route('kasir.pembelian.store') }}">
                    Ya, Proses
                </button>                
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada elemen dengan id pelanggan_id
        $('#pelanggan_id').select2({
            placeholder: "-- Pilih Pelanggan --",
            allowClear: true,
            width: '100%'
        });

        // Inisialisasi Select2 pada elemen dengan class produk-select
        $('.produk-select').select2({
            placeholder: "-- Pilih Produk --",
            allowClear: true,
            width: '100%'
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        let hargaProduk = JSON.parse(document.querySelector("#harga_produk_json").value);
        let diskonSelect = document.querySelector("#diskon");
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
            diskonSelect.disabled = !adaProduk;
        }

        function updateDiskonOptions(totalBayar, poinMembership) {
            diskonSelect.innerHTML = '<option value="0">Pilih Diskon</option>';
            let diskonTersedia = false;

            if (totalBayar >= 100000 && poinMembership >= 10000) {
                diskonSelect.innerHTML += '<option value="10">Diskon 10% (Minimal belanja 100k + Gunakan 10k poin)</option>';
                diskonTersedia = true;
            }
            if (totalBayar >= 300000 && poinMembership >= 20000) {
                diskonSelect.innerHTML += '<option value="20">Diskon 20% (Minimal belanja 300k + Gunakan 20k poin)</option>';
                diskonTersedia = true;
            }
            // if (totalBayar >= 500000 && poinMembership >= 30000) {
            //     diskonSelect.innerHTML += '<option value="30">Diskon 30% (500k + 30k poin)</option>';
            //     diskonTersedia = true;
            // }

            if (!diskonTersedia) {
                diskonSelect.innerHTML = '<option value="0">Persyaratan diskon belum terpenuhi</option>';
            }

            diskonSelect.disabled = !diskonTersedia;
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

            let pelangganSelect = document.querySelector("#pelanggan_id");
            let poinMembership = parseFloat(pelangganSelect.selectedOptions[0]?.dataset.poin) || 0;

            // Simpan nilai diskon yang sudah dipilih sebelum memperbarui opsi
            let selectedDiskon = diskonSelect.value;  

            // Update opsi diskon berdasarkan totalBayar dan poinMembership
            updateDiskonOptions(totalBayar, poinMembership);

            // Kembalikan nilai diskon yang sebelumnya dipilih (jika masih ada dalam opsi)
            if ([...diskonSelect.options].some(opt => opt.value === selectedDiskon)) {
                diskonSelect.value = selectedDiskon;
            }

            // Ambil nilai diskon setelah update dropdown
            let diskonPersen = parseFloat(diskonSelect.value) || 0;

            let diskonNominal = (diskonPersen / 100) * totalBayar;
            let totalSetelahDiskon = totalBayar - diskonNominal;
            let totalAkhir = totalSetelahDiskon * 1.12; // Tambahkan pajak 12%

            // Ambil poin yang digunakan untuk diskon
            let poinDipakai = parseFloat(diskonSelect.selectedOptions[0]?.dataset.poin) || 0;
            let sisaPoin = poinMembership - poinDipakai;

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

        document.querySelector("#diskon").addEventListener("change", function () {
            let diskonPersen = parseFloat(this.value) || 0;
            updateTotal();
        });

        $(document).ready(function () {
            $('#pelanggan_id').select2();

            // Event listener untuk perubahan pelanggan di Select2
            $('#pelanggan_id').on('select2:select', function (event) {
                let pelangganSelect = event.params.data.element;
                let pelangganTipe = $(pelangganSelect).data("tipe") || "tipe_3";
                let pelangganPoin = $(pelangganSelect).data("poin") || 0;
                let hargaProduk = JSON.parse($("#harga_produk_json").val());

                // Update poin pelanggan saat pelanggan dipilih
                $("#poin_pelanggan").val(pelangganPoin);

                // Update harga produk yang dipilih
                $(".produk-select").each(function () {
                    let row = $(this).closest("tr");
                    let produkId = $(this).val();
                    let selectedOption = $(this).find(":selected");

                    let tipeHarga = pelangganTipe;
                    let harga = hargaProduk[produkId]?.[tipeHarga] || 0;
                    let stok = hargaProduk[produkId]?.stok || 0;
                    let satuan = selectedOption.data("satuan") || "";

                    row.find(".harga").data("harga", harga);
                    row.find(".stok-satuan").text(`${stok} ${satuan}`);
                    row.find(".jumlah").attr("max", stok);
                });

                updateTotal();
            });

            // Event listener untuk jumlah produk
            $(document).on("change", ".jumlah", function () {
                let row = $(this).closest("tr");
                let jumlahInput = $(this);
                let stokTersedia = parseInt(row.find(".stok-satuan").text().split(" ")[0]) || 0;
                let jumlah = parseInt(jumlahInput.val()) || 0;

                if (jumlah > stokTersedia) {
                    alert("Stok barang tidak cukup!");
                    jumlahInput.val(stokTersedia);
                }
                updateTotal();
            });
        });

        // Gunakan event `select2:select` untuk produk yang dipilih
        $(document).on("select2:select", ".produk-select", function(event) {
            let row = event.target.closest("tr");
            let produkId = event.target.value;
            let selectedOption = $(this).find(":selected");

            let pelangganSelect = document.querySelector("#pelanggan_id");
            let pelangganTipe = pelangganSelect.selectedOptions[0]?.dataset.tipe || "tipe_3";
            let hargaProduk = JSON.parse(document.querySelector("#harga_produk_json").value);

            let tipeHarga = pelangganTipe;
            let harga = hargaProduk[produkId]?.[tipeHarga] || 0;
            let stok = hargaProduk[produkId]?.stok?.reduce((total, item) => total + item.jumlah_stok, 0) || 0;
            let satuan = selectedOption.attr("data-satuan") || "";

            row.querySelector(".harga").dataset.harga = harga;
            row.querySelector(".stok-satuan").innerText = `${stok} ${satuan}`;
            row.querySelector(".jumlah").setAttribute("max", stok);
            updateTotal();
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
                    <select name="produk_id[]" class="form-control produk-select select2" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produk as $pr)
                            <option value="{{ $pr->id }}" data-stok="{{ $pr->stok->sum('jumlah_stok') }}" data-satuan="{{ $pr->satuan }}">
                                {{ $pr->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="stok-satuan">-</td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" required></td>
                <td class="harga" data-harga="0">0</td>
                <td class="total">0</td>
                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
            `;
            document.querySelector("#produk-list").appendChild(row);

            // Inisialisasi Select2 pada elemen select yang baru ditambahkan
            $(row).find('.produk-select').select2({
                placeholder: "-- Pilih Produk --",
                allowClear: true,
                width: '100%'
            });

            checkProduk();
        });

        $('#pelanggan_id').on('select2:select', function () {
            document.querySelector("#produk-list").innerHTML = "";
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

        document.querySelector("#konfirmasiProses").addEventListener("click", function () {
            let form = document.querySelector("#form-transaksi"); // Pastikan form memiliki ID ini
            form.submit();
        });
    });
</script>
@endsection

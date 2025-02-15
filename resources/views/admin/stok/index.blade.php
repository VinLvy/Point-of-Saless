@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Stok Barang</h2>
    <a href="{{ route('admin.stok.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Tambah Stok
    </a>
    
    <div class="table-responsive" style="border-radius: 8px;">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-primary text-center">
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Stok</th>
                    <th>Tanggal Kedaluwarsa</th>
                    <th>Tanggal Pembelian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stok->sortBy('itemBarang.kode_barang') as $item)
                <tr class="text-center align-middle">
                    <td><span class="badge bg-primary">{{ $item->itemBarang->kode_barang }}</span></td>
                    <td class="text-start">{{ $item->itemBarang->nama_barang }}</td>
                    <td><strong class="text-success">{{ $item->jumlah_stok }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($item->expired_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->buy_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.stok.edit', $item->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus"
                            data-id="{{ $item->id }}"
                            data-nama="{{ $item->itemBarang->nama_barang }}"
                            data-kode="{{ $item->itemBarang->kode_barang }}"
                            data-bs-toggle="modal" data-bs-target="#modalHapus">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus stok <strong id="nama-barang"></strong> (<span id="kode-barang"></span>)?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="form-hapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalHapus = document.getElementById("modalHapus");
    const namaBarangEl = document.getElementById("nama-barang");
    const kodeBarangEl = document.getElementById("kode-barang");
    const formHapus = document.getElementById("form-hapus");

    document.querySelectorAll(".btn-hapus").forEach(button => {
        button.addEventListener("click", function () {
            const itemId = this.getAttribute("data-id");
            const itemNama = this.getAttribute("data-nama");
            const itemKode = this.getAttribute("data-kode");

            // Update isi modal dengan data barang yang dipilih
            namaBarangEl.textContent = itemNama;
            kodeBarangEl.textContent = itemKode;

            // Set action form hapus
            formHapus.setAttribute("action", `/admin/stok/${itemId}`);
        });
    });
});
</script>
@endsection

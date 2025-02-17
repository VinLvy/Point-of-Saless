@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-inboxes"></i> Daftar Stok Barang</h4>
            <div>
                <button onclick="window.print()" class="btn btn-secondary btn-sm no-print">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
                <a href="{{ route('admin.stok.create') }}" class="btn btn-success btn-sm no-print">
                    <i class="bi bi-plus-circle"></i> Tambah Stok
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Alert pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel Stok --}}
            <div class="table-responsive" style="border-radius: 8px;">
                <table class="table table-bordered align-middle" id="stokTable">
                    <thead class="table-primary text-center text-white">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Stok</th>
                            <th>Tanggal Pembelian</th>
                            <th>Tanggal Kedaluwarsa</th>
                            <th>Sisa Hari</th>
                            <th class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok->sortBy('itemBarang.kode_barang') as $index => $item)
                            <tr data-expired-date="{{ $item->expired_date ? date('Y-m-d', strtotime($item->expired_date)) : '' }}">
                                <td class="fw-bold">{{ $item->itemBarang->kode_barang }}</td>
                                <td>{{ $item->itemBarang->nama_barang }}</td>
                                <td class="text-center text-success fw-bold">{{ $item->jumlah_stok }}</td>
                                <td class="text-center">{{ $item->buy_date ? date('d M Y', strtotime($item->buy_date)) : '-' }}</td>
                                <td class="text-center">{{ $item->expired_date ? date('d M Y', strtotime($item->expired_date)) : '-' }}</td>
                                <td class="text-center days-diff"></td>
                                <td class="text-center no-print">
                                    <a href="{{ route('admin.stok.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" data-nama="{{ $item->itemBarang->nama_barang }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus stok <strong id="namaBarang"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- CSS untuk Print --}}
<style>
    @media print {
        .no-print {
            display: none !important;
        }

    .table-responsive {
        overflow: visible !important; /* Pastikan tabel tidak tersembunyi */
    }

    table {
        width: 100% !important; /* Pastikan tabel mengisi seluruh halaman */
        table-layout: fixed;
        word-wrap: break-word;
    }

    th, td {
        word-break: break-word;
    }

    @page {
        size: landscape; /* Mengubah orientasi halaman ke landscape */
        margin: 10mm; /* Mengurangi margin agar tabel lebih muat */
    }
}
</style>

{{-- Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            document.getElementById('namaBarang').textContent = nama;
            document.getElementById('deleteForm').action = `/admin/stok/${id}`;
        });
    });

    // Periksa setiap baris tabel stok
    document.querySelectorAll("#stokTable tbody tr").forEach(row => {
        const daysDiffCell = row.querySelector(".days-diff");

        if (daysDiffCell) {
            const expiredDate = row.getAttribute("data-expired-date");

            if (expiredDate) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const expDate = new Date(expiredDate);
                expDate.setHours(0, 0, 0, 0);

                const timeDiff = expDate.getTime() - today.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                // Set nilai dalam kolom "Sisa Hari"
                daysDiffCell.textContent = daysDiff >= 0 ? daysDiff + " hari" : "Kedaluwarsa";

                // Jika tanggal kedaluwarsa dalam 7 hari ke depan, ubah warna baris
                if (daysDiff <= 7) {
                    row.style.backgroundColor = "#dc3545"; // Merah
                    row.style.color = "white";
                }
            }
        }
    });

    // Auto-hide alert setelah 3 detik
    setTimeout(() => {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
});

</script>
@endsection
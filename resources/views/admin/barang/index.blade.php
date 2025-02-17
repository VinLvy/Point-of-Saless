@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-box"></i> Daftar Barang</h4>
            <a href="{{ route('admin.barang.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Barang
            </a>
        </div>
        <div class="card-body">
            {{-- Alert pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel Barang --}}
            <div class="table-responsive" style="border-radius: 8px;">
                <table class="table table-hover table-bordered align-middle table-striped">
                    <thead class="table-primary text-center text-white">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual 1</th>
                            <th>Harga Jual 2</th>
                            <th>Harga Jual 3</th>
                            <th>Min. Stok</th>
                            <th>Stok</th>
                            {{-- <th>Buy Date</th>
                            <th>Exp. Date</th> --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $index => $item)
                            @php
                                $totalStok = $item->stok->sum('jumlah_stok');
                                $expDateTercepat = $item->stok->min('expired_date') ?? '-';
                                $buyDateTerlama = $item->stok->min('buy_date') ?? '-';
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kategori->nama_kategori }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_1, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_2, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga_jual_3, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->minimal_stok }}</td>
                                <td class="text-center {{ $totalStok <= $item->minimal_stok ? 'text-danger fw-bold' : '' }}">
                                    {{ $totalStok }}
                                </td>
                                {{-- <td class="text-center">{{ $buyDateTerlama }}</td>
                                <td class="text-center">{{ $expDateTercepat }}</td> --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.barang.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" data-nama="{{ $item->nama_barang }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted">Belum ada data barang.</td>
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
                <p>Apakah Anda yakin ingin menghapus <strong id="namaBarang"></strong>?</p>
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

{{-- Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                document.getElementById('namaBarang').textContent = nama;
                document.getElementById('deleteForm').action = `/admin/barang/${id}`;
            });
        });
    });

    // Auto-hide alert
    setTimeout(() => {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-box-seam-fill"></i> Daftar Kategori Barang</h4>
        </div>
        <div class="card-body">
            {{-- Alert pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form Tambah Kategori --}}
            <div class="mb-4 p-3 bg-light border rounded">
                <h5> Tambah Kategori</h5>
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-lg"></i> Tambah</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Kategori Barang --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>Kode Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategori as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $item->kode_kategori }}</td>
                                <td>{{ $item->nama_kategori }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.kategori.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada kategori barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk fade-out alert --}}
<script>
    setTimeout(() => {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
@endsection

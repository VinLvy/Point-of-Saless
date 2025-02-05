@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Daftar Kategori Barang</h4>
        </div>
        <div class="card-body">
            {{-- Alert pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form Tambah Kategori --}}
            <form action="{{ route('admin.kategori.store') }}" method="POST" class="mb-3">
                @csrf
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah</button>
                    </div>
                </div>
            </form>

            {{-- Tabel Kategori Barang --}}
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
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
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->kode_kategori }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada kategori barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
